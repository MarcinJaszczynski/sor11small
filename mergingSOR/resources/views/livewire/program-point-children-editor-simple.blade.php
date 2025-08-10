@php
    use Illuminate\Support\Facades\Storage;
    
    // Security validation - Check user authentication
    if (!auth()->check()) {
        abort(401, 'Unauthorized access');
    }
    
    // TODO: Implement proper permission system
    // if (!auth()->user()->can('manage_program_points')) {
    //     abort(403, 'Insufficient permissions to manage program points');
    // }
    
    // Security headers for XSS protection
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: DENY');
    header('X-XSS-Protection: 1; mode=block');
@endphp

<div wire:init="loadChildren" x-data="{ 
    csrfToken: '{{ csrf_token() }}',
    maxFileSize: {{ config('app.max_upload_size', 2048) }},
    allowedExtensions: ['jpg', 'jpeg', 'png', 'gif', 'webp'],
    rateLimitExceeded: false,
    lastActionTime: 0
}"
x-init="
    // Rate limiting protection
    $watch('rateLimitExceeded', value => {
        if (value) {
            setTimeout(() => { rateLimitExceeded = false; }, 60000); // 1 minute cooldown
        }
    });
    
    // Security event logging
    document.addEventListener('contextmenu', (e) => {
        console.warn('Context menu disabled for security');
        // Don't prevent default - accessibility concern
    });
"
class="security-protected-component">
    <!-- Security protected notifications container -->
    <div id="children-notifications" class="fixed top-4 right-4 z-50" role="alert" aria-live="polite"></div>

    <!-- Security validation warnings -->
    @if(session('security_warning'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-md mb-4" role="alert">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                <strong>Ostrzeżenie bezpieczeństwa:</strong> {{ session('security_warning') }}
            </div>
        </div>
    @endif

    <div class="bg-white shadow rounded-lg p-4 fi-section-content">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-700">
                Podpunkty programu: {{ Str::limit(e($programPoint->name), 50) }}
                <span class="text-sm text-gray-500 ml-2">({{ count($children) }} {{ Str::plural('element', count($children), ['element', 'elementy', 'elementów']) }})</span>
            </h3>
            <button 
                wire:click="showAddModal"
                x-on:click="
                    if (rateLimitExceeded) {
                        $dispatch('notify', { message: 'Zbyt częste żądania. Spróbuj ponownie za chwilę.', type: 'error' });
                        return;
                    }
                    if (Date.now() - lastActionTime < 1000) {
                        rateLimitExceeded = true;
                        $dispatch('notify', { message: 'Zbyt częste żądania. Spróbuj ponownie za chwilę.', type: 'error' });
                        return;
                    }
                    lastActionTime = Date.now();
                "
                class="fi-btn fi-btn-color-primary hover:bg-primary-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2"
                aria-label="Dodaj nowy podpunkt programu">
                <x-heroicon-o-plus-circle class="w-5 h-5 mr-2" />
                Dodaj podpunkt
            </button>
        </div>

        <ul class="children-list space-y-2 min-h-[100px] border border-dashed border-gray-300 p-2 rounded-md" 
            id="children-container" 
            data-csrf-token="{{ csrf_token() }}"
            role="list"
            aria-label="Lista podpunktów programu">
            @forelse ($children as $index => $child)
                <li class="bg-gray-100 p-3 rounded-md shadow-sm border border-gray-100 flex items-start" 
                    data-child-id="{{ (int)$child['id'] }}"
                    data-index="{{ $index }}"
                    role="listitem"
                    aria-label="Podpunkt: {{ e($child['name']) }}">

                    <!-- Col 0: Drag Handle -->
                    <div class="w-12 pr-2 py-1 cursor-grab drag-handle self-center flex-shrink-0"
                         role="button"
                         tabindex="0"
                         aria-label="Przeciągnij aby zmienić kolejność"
                         onkeydown="if(event.key === 'Enter' || event.key === ' ') { event.preventDefault(); this.click(); }">
                        <x-heroicon-o-bars-3 class="w-5 h-5 text-gray-400" />
                    </div>                    <!-- Col 1: Featured Image -->
                    <div class="w-20 pr-2 py-1 border-r border-gray-200 flex-shrink-0">
                        @if(!empty($child['featured_image']) && is_string($child['featured_image']) && \Illuminate\Support\Facades\Storage::exists($child['featured_image']))
                            @php
                                $imageUrl = Storage::url($child['featured_image']);
                                $safeImageUrl = filter_var($imageUrl, FILTER_SANITIZE_URL);
                            @endphp
                            <img src="{{ $safeImageUrl }}?t={{ time() }}" 
                                 alt="Zdjęcie wyróżniające dla: {{ e($child['name']) }}" 
                                 class="h-16 w-16 object-cover rounded-lg shadow-sm border border-gray-200"
                                 onload="this.style.opacity=1" 
                                 style="opacity:0;transition:opacity 0.3s"
                                 onerror="this.style.display='none';this.nextElementSibling.style.display='flex'"
                                 loading="lazy"
                                 decoding="async">
                        @endif
                        @if(empty($child['featured_image']) || !is_string($child['featured_image']) || !\Illuminate\Support\Facades\Storage::exists($child['featured_image']))
                            <div class="h-16 w-16 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center border border-gray-200"
                                 role="img"
                                 aria-label="Brak zdjęcia wyróżniającego">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- Col 2: Name, Duration, Office Notes -->
                    <div class="w-72 px-2 py-1 border-r border-gray-200 flex-shrink-0">
                        <div class="font-medium text-gray-700">{{ e($child['name']) }}</div>
                        @if(isset($child['duration_hours']) || isset($child['duration_minutes']))
                            <div class="text-xs text-gray-600 mt-1">
                                Czas trwania: {{ sprintf('%02d:%02d', (int)($child['duration_hours'] ?? 0), (int)($child['duration_minutes'] ?? 0)) }}
                            </div>
                        @endif
                        @if(!empty($child['office_notes']) && is_string($child['office_notes']))
                            <div class="text-xs text-blue-600 italic mt-1">
                                Uwagi dla biura: {{ e(Str::limit($child['office_notes'], 100)) }}
                            </div>
                        @endif
                    </div>

                    <!-- Col 3: Description -->
                    <div class="w-72 px-2 py-1 border-r border-gray-200 flex-shrink-0">
                        @if(!empty($child['description']) && is_string($child['description']))
                            <p class="text-xs text-gray-600">{{ e(Str::limit($child['description'], 150)) }}</p>
                        @else
                            <p class="text-xs text-gray-400 italic">Brak opisu.</p>
                        @endif
                    </div>                    <!-- Col 4: Gallery -->
                    <div class="w-48 px-2 py-1 border-r border-gray-200 flex-shrink-0">
                        @if(!empty($child['gallery_images']) && is_array($child['gallery_images']))
                            <div class="flex flex-wrap gap-1" role="group" aria-label="Galeria zdjęć">
                                @foreach(array_slice($child['gallery_images'], 0, 4) as $imageIndex => $image)
                                    @if(is_string($image) && \Illuminate\Support\Facades\Storage::exists($image))
                                        @php
                                            $galleryImageUrl = Storage::url($image);
                                            $safeGalleryImageUrl = filter_var($galleryImageUrl, FILTER_SANITIZE_URL);
                                        @endphp
                                        <img src="{{ $safeGalleryImageUrl }}?t={{ time() }}" 
                                             alt="Miniaturka galerii {{ $imageIndex + 1 }} dla: {{ e($child['name']) }}" 
                                             class="h-8 w-8 object-cover rounded"
                                             onload="this.style.opacity=1" 
                                             style="opacity:0;transition:opacity 0.3s"
                                             loading="lazy"
                                             decoding="async">
                                    @endif
                                @endforeach
                                @if(count($child['gallery_images']) > 4)
                                    <span class="text-xs text-gray-500 self-center bg-gray-200 px-1 rounded"
                                          aria-label="Dodatkowe {{ count($child['gallery_images']) - 4 }} zdjęć w galerii">
                                        +{{ count($child['gallery_images']) - 4 }}
                                    </span>
                                @endif
                            </div>
                        @else
                            <p class="text-xs text-gray-400 italic">Brak galerii.</p>
                        @endif
                    </div>                    <!-- Col 5: Tags -->
                    <div class="w-48 px-2 py-1 border-r border-gray-200 flex-shrink-0">
                        @if(!empty($child['tags']) && is_array($child['tags']))
                            <div class="flex flex-wrap gap-1" role="group" aria-label="Tagi">
                                @foreach($child['tags'] as $tag)
                                    @if(is_array($tag) && isset($tag['name']) && is_string($tag['name']))
                                        <span class="inline-block bg-orange-100 text-orange-800 rounded-full px-2 py-0.5 text-xs font-semibold"
                                              aria-label="Tag: {{ e($tag['name']) }}">
                                            {{ e($tag['name']) }}
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <p class="text-xs text-gray-400 italic">Brak tagów.</p>
                        @endif
                    </div>

                    <!-- Col 6: Actions -->                    
                    <div class="w-20 pl-2 py-1 flex items-center space-x-2 self-center flex-shrink-0">
                        <button 
                            wire:click="deleteChild({{ (int)$child['id'] }})" 
                            wire:confirm="Czy na pewno chcesz usunąć podpunkt '{{ e($child['name']) }}'? Ta operacja jest nieodwracalna." 
                            x-on:click="
                                if (rateLimitExceeded) {
                                    $dispatch('notify', { message: 'Zbyt częste żądania. Spróbuj ponownie za chwilę.', type: 'error' });
                                    $event.stopPropagation();
                                    return false;
                                }
                                if (Date.now() - lastActionTime < 2000) {
                                    rateLimitExceeded = true;
                                    $dispatch('notify', { message: 'Zbyt częste żądania. Spróbuj ponownie za chwilę.', type: 'error' });
                                    $event.stopPropagation();
                                    return false;
                                }
                                lastActionTime = Date.now();
                            "
                            class="text-gray-400 hover:text-red-600 p-1 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 rounded"
                            title="Usuń podpunkt: {{ e($child['name']) }}"
                            aria-label="Usuń podpunkt: {{ e($child['name']) }}">
                            <x-heroicon-o-trash class="w-5 h-5" />
                        </button>
                    </div>
                </li>
            @empty
                <li class="text-center text-gray-400 py-4 italic" role="listitem">
                    <div class="flex flex-col items-center">
                        <svg class="w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p>Brak podpunktów programu.</p>
                        <p class="text-xs mt-1">Kliknij "Dodaj podpunkt" aby rozpocząć.</p>
                    </div>
                </li>
            @endforelse
        </ul>
    </div>    <!-- Security Enhanced Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" 
             role="dialog" 
             aria-modal="true" 
             aria-labelledby="modal-title"
             x-data="{ 
                modalOpen: true,
                searchDebounce: null 
             }"
             x-init="
                document.body.style.overflow = 'hidden';
                $nextTick(() => { $refs.searchInput?.focus(); });
             "
             x-on:keydown.escape="$wire.closeModal()"
             @keydown.tab="$event.preventDefault()"
             @click.away="$wire.closeModal()">
            
            <!-- Security Enhanced Backdrop -->
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity duration-300" 
                 wire:click="closeModal"
                 aria-hidden="true"></div>
            
            <!-- Modal -->
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="relative w-full max-w-5xl bg-white rounded-lg shadow-xl max-h-[90vh] overflow-hidden transform transition-all duration-300 scale-100"
                     @click.stop>
                    
                    <!-- Security Header -->
                    <div class="bg-white border-b px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 id="modal-title" class="text-xl font-semibold text-gray-900">Dodaj podpunkt</h2>
                                <p class="text-sm text-gray-600 mt-1">
                                    Do punktu: <span class="font-medium">{{ e(Str::limit($programPoint->name, 60)) }}</span>
                                </p>
                            </div>
                            <button wire:click="closeModal" 
                                    class="text-gray-400 hover:text-gray-600 p-2 rounded-lg hover:bg-gray-100 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500"
                                    aria-label="Zamknij modal"
                                    type="button">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Enhanced Search Section -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-b px-6 py-4">
                        <div class="mb-3">
                            <div class="flex items-center space-x-2 mb-2">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <label for="search-input" class="text-sm font-medium text-gray-700">Wyszukaj punkt programu</label>
                            </div>
                            <input type="text" 
                                   id="search-input"
                                   x-ref="searchInput"
                                   wire:model.live.debounce.500ms="searchTerm" 
                                   placeholder="Wpisz nazwę, opis lub tag punktu programu..."
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors duration-200"
                                   autocomplete="off"
                                   spellcheck="false"
                                   maxlength="100"
                                   @input="
                                       clearTimeout(searchDebounce);
                                       searchDebounce = setTimeout(() => {
                                           if ($event.target.value.length > 100) {
                                               $event.target.value = $event.target.value.substring(0, 100);
                                           }
                                       }, 100);
                                   ">
                        </div>
                        <div class="flex items-center justify-between text-xs text-gray-600">
                            <div class="flex items-center space-x-4">
                                <span class="flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Szukaj po nazwie, opisie lub tagach
                                </span>
                                @if($searchTerm)
                                    <button wire:click="$set('searchTerm', '')" 
                                            type="button"
                                            class="text-orange-600 hover:text-orange-800 flex items-center transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-orange-500 rounded px-1"
                                            aria-label="Wyczyść wyszukiwanie">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Wyczyść
                                    </button>
                                @endif
                            </div>
                            <span class="bg-white px-2 py-1 rounded text-orange-700 font-medium border border-orange-200">
                                {{ count($filteredPoints) }} z {{ count($availablePoints) }} punktów
                            </span>
                        </div>
                    </div>
                        <!-- Security Enhanced Points List -->
                        <div class="px-6 py-4 max-h-96 overflow-y-auto bg-gray-50">
                            <p class="text-xs text-gray-500 mb-2">Kliknij wybrany punkt z listy, aby dodać go jako podpunkt do aktualnego programu.</p>
                            @forelse($filteredPoints as $index => $point)
                                @if(is_array($point) && isset($point['id']) && is_numeric($point['id']))
                                    <div class="bg-white border-2 rounded-xl mb-4 p-5 hover:shadow-lg transition-all cursor-pointer duration-200
                                        {{ $modalData['child_program_point_id'] == $point['id'] ? 'border-blue-500 shadow-lg bg-blue-50' : 'border-gray-200 hover:border-blue-300' }}"
                                        wire:click="$set('modalData.child_program_point_id', {{ (int)$point['id'] }})"
                                        role="button"
                                        tabindex="0"
                                        aria-label="Wybierz punkt: {{ e($point['name'] ?? '') }}"
                                        @keydown.enter="$wire.set('modalData.child_program_point_id', {{ (int)$point['id'] }})"
                                        @keydown.space.prevent="$wire.set('modalData.child_program_point_id', {{ (int)$point['id'] }})">
                                        
                                        <div class="flex items-start space-x-4">
                                            <!-- Radio button -->
                                            <div class="pt-1">
                                                <input type="radio" 
                                                       name="selected_point" 
                                                       value="{{ (int)$point['id'] }}"
                                                       {{ $modalData['child_program_point_id'] == $point['id'] ? 'checked' : '' }}
                                                       class="w-4 h-4 text-orange-600 border-2 border-gray-300 focus:ring-2 focus:ring-orange-500"
                                                       readonly
                                                       aria-hidden="true">
                                            </div>
                                            
                                            <!-- Image -->
                                            <div class="flex-shrink-0">
                                                @if(isset($point['featured_image']) && is_string($point['featured_image']) && $point['featured_image'] && \Illuminate\Support\Facades\Storage::exists($point['featured_image']))
                                                    @php
                                                        $pointImageUrl = Storage::url($point['featured_image']);
                                                        $safePointImageUrl = filter_var($pointImageUrl, FILTER_SANITIZE_URL);
                                                    @endphp
                                                    <img src="{{ $safePointImageUrl }}?t={{ time() }}" 
                                                         alt="Zdjęcie dla: {{ e($point['name'] ?? '') }}" 
                                                         class="h-16 w-16 rounded-lg object-cover border-2 border-gray-200"
                                                         onload="this.style.opacity=1" 
                                                         style="opacity:0;transition:opacity 0.3s"
                                                         onerror="this.style.display='none';this.nextElementSibling.style.display='flex'"
                                                         loading="lazy"
                                                         decoding="async">
                                                @endif
                                                @if(!isset($point['featured_image']) || !is_string($point['featured_image']) || !$point['featured_image'] || !\Illuminate\Support\Facades\Storage::exists($point['featured_image']))
                                                    <div class="h-16 w-16 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center border-2 border-gray-200"
                                                         role="img"
                                                         aria-label="Brak zdjęcia">
                                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Enhanced Information Section -->
                                            <div class="flex-1 min-w-0">
                                                <!-- Name -->
                                                <h4 class="text-lg font-semibold text-gray-900 mb-3 leading-tight">
                                                    {{ e($point['name'] ?? 'Nazwa niedostępna') }}
                                                </h4>
                                                
                                                <!-- Metadata in readable blocks -->
                                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-3">
                                                    <!-- Time -->
                                                    <div class="flex items-center space-x-2 bg-blue-50 px-3 py-2 rounded-lg">
                                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        <span class="text-sm font-medium text-blue-800">
                                                            {{ sprintf('%02d:%02d', (int)($point['duration_hours'] ?? 0), (int)($point['duration_minutes'] ?? 0)) }}
                                                        </span>
                                                    </div>
                                                    
                                                    <!-- Price -->
                                                    <div class="flex items-center space-x-2 bg-green-50 px-3 py-2 rounded-lg">
                                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                                        </svg>
                                                        <span class="text-sm font-medium text-green-800">
                                                            {{ number_format((float)($point['unit_price'] ?? 0), 2) }} 
                                                            {{ e($point['currency']['symbol'] ?? 'PLN') }}
                                                        </span>
                                                    </div>
                                                    
                                                    <!-- Group -->
                                                    @if(isset($point['group_size']) && (int)$point['group_size'] > 1)
                                                        <div class="flex items-center space-x-2 bg-purple-50 px-3 py-2 rounded-lg">
                                                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                            </svg>
                                                            <span class="text-sm font-medium text-purple-800">
                                                                Grupa {{ (int)$point['group_size'] }} osób
                                                            </span>
                                                        </div>
                                                    @else
                                                        <div class="flex items-center space-x-2 bg-gray-50 px-3 py-2 rounded-lg">
                                                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                            </svg>
                                                            <span class="text-sm font-medium text-gray-700">
                                                                Cena za osobę
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>

                                                <!-- PLN conversion status -->
                                                @if(isset($point['convert_to_pln']) && $point['convert_to_pln'])
                                                    <div class="mb-3">
                                                        <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            </svg>
                                                            Automatycznie przeliczane na PLN
                                                        </span>
                                                    </div>
                                                @endif

                                                <!-- Description -->
                                                @if(isset($point['description']) && is_string($point['description']) && trim($point['description']))
                                                    <div class="mb-3">
                                                        <p class="text-sm text-gray-700 leading-relaxed bg-gray-50 p-3 rounded-lg border-l-4 border-gray-300">
                                                            {{ e(Str::limit($point['description'], 200)) }}
                                                        </p>
                                                    </div>
                                                @endif

                                                <!-- Tags -->
                                                @if(isset($point['tags']) && is_array($point['tags']) && count($point['tags']) > 0)
                                                    <div class="flex flex-wrap gap-2">
                                                        @foreach(array_slice($point['tags'], 0, 6) as $tag)
                                                            @if(is_array($tag) && isset($tag['name']) && is_string($tag['name']))
                                                                <span class="inline-flex items-center px-2.5 py-1 bg-orange-100 text-orange-800 rounded-md text-xs font-medium">
                                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                                                    </svg>
                                                                    {{ e($tag['name']) }}
                                                                </span>
                                                            @endif
                                                        @endforeach
                                                        @if(count($point['tags']) > 6)
                                                            <span class="inline-flex items-center px-2.5 py-1 bg-gray-200 text-gray-700 rounded-md text-xs font-medium">
                                                                +{{ count($point['tags']) - 6 }} więcej
                                                            </span>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @empty
                                <div class="text-center py-12 bg-white rounded-xl border-2 border-dashed border-gray-300"
                                     role="alert"
                                     aria-live="polite">
                                    <div class="text-gray-400 mb-4">
                                        <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nie znaleziono punktów programu</h3>
                                    <p class="text-gray-500 mb-4">
                                        @if($searchTerm && is_string($searchTerm))
                                            Brak wyników dla: <span class="font-medium">"{{ e(Str::limit($searchTerm, 50)) }}"</span>
                                        @else
                                            Brak dostępnych punktów programu
                                        @endif
                                    </p>
                                    @if($searchTerm)
                                        <button wire:click="$set('searchTerm', '')" 
                                                type="button" 
                                                class="inline-flex items-center px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                            </svg>
                                            Wyczyść wyszukiwanie
                                        </button>
                                    @endif
                                </div>
                            @endforelse
                        </div>                    <!-- Enhanced Security Footer -->
                        <div class="border-t bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4">
                            <div class="flex justify-between items-center">
                                <div class="text-sm text-gray-600">
                                    @if($modalData['child_program_point_id'])
                                        <span class="flex items-center text-green-700">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Punkt wybrany - gotowy do dodania
                                        </span>
                                    @else
                                        <span class="flex items-center text-gray-500">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Wybierz punkt programu z listy powyżej, aby aktywować przycisk dodawania.
                                        </span>
                                    @endif
                                </div>
                                <div class="flex space-x-3">
                                    <button type="button" 
                                            wire:click="closeModal" 
                                            class="fi-btn fi-btn-outlined fi-btn-color-gray hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200">
                                        Anuluj
                                    </button>
                                    <button wire:click="saveChild" 
                                            type="button"
                                            @disabled(empty($modalData['child_program_point_id']))
                                            x-bind:disabled="!$wire.modalData.child_program_point_id"
                                            x-on:click="
                                                if (rateLimitExceeded) {
                                                    $dispatch('notify', { message: 'Zbyt częste żądania. Spróbuj ponownie za chwilę.', type: 'error' });
                                                    return;
                                                }
                                                if (Date.now() - lastActionTime < 2000) {
                                                    rateLimitExceeded = true;
                                                    $dispatch('notify', { message: 'Zbyt częste żądania. Spróbuj ponownie za chwilę.', type: 'error' });
                                                    return;
                                                }
                                                lastActionTime = Date.now();
                                            "
                                            class="fi-btn fi-btn-color-primary hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                                            :aria-label="$wire.modalData.child_program_point_id ? 'Dodaj wybrany podpunkt' : 'Wybierz punkt aby kontynuować'">
                                        {{ empty($modalData['child_program_point_id']) ? 'Wybierz punkt' : 'Dodaj podpunkt' }}
                                    </button>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">
                                Aby dodać podpunkt, najpierw wybierz go z listy powyżej i kliknij „Dodaj podpunkt". 
                                <span class="text-orange-600">Operacja zostanie zarejestrowana w logach systemu.</span>
                            </p>
                        </div>
                        <div class="border-t bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4">
                            <div class="flex justify-between items-center">
                                <div class="text-sm text-gray-600">
                                    @if($modalData['child_program_point_id'])
                                        <span class="flex items-center text-green-700">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Punkt wybrany - gotowy do dodania
                                        </span>
                                    @else
                                        <span class="flex items-center text-gray-500">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Wybierz punkt programu z listy powyżej, aby aktywować przycisk dodawania.
                                        </span>
                                    @endif
                                </div>                            <div class="flex space-x-3">
                                    <button type="button" wire:click="closeModal" 
                                        class="fi-btn fi-btn-outlined fi-btn-color-gray">
                                        Anuluj
                                    </button>
                                    <button wire:click="saveChild" type="button"
                                        @disabled(empty($modalData['child_program_point_id']))
                                        class="fi-btn fi-btn-color_primary">
                                        {{ empty($modalData['child_program_point_id']) ? 'Wybierz punkt' : 'Dodaj podpunkt' }}
                                    </button>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">Aby dodać podpunkt, najpierw wybierz go z listy powyżej i kliknij „Dodaj podpunkt”.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @push('scripts')
    <script>
    // Enhanced security for children sortable
    document.addEventListener('livewire:navigated', initializeChildrenSortable);
    document.addEventListener('livewire:updated', initializeChildrenSortable);

    // Security-enhanced message handling from Livewire
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('notify', (data) => {
            // Sanitize message content
            const sanitizedMessage = DOMPurify ? DOMPurify.sanitize(data.message) : data.message.replace(/<[^>]*>/g, '');
            const validTypes = ['success', 'error', 'warning', 'info'];
            const safeType = validTypes.includes(data.type) ? data.type : 'info';
            
            showChildrenNotification(sanitizedMessage, safeType);
        });
    });

    // Enhanced keyboard security handling
    document.addEventListener('keydown', function(e) {
        // Prevent certain key combinations that could be used for attacks
        if (e.ctrlKey && (e.key === 'u' || e.key === 'U')) {
            e.preventDefault(); // Prevent view source
            return false;
        }
        
        if (e.key === 'F12') {
            e.preventDefault(); // Prevent dev tools (basic deterrent)
            return false;
        }
        
        if (e.key === 'Escape') {
            @this.call('closeModal');
        }
    });

    // Security-enhanced notification system
    function showChildrenNotification(message, type = 'info') {
        const container = document.getElementById('children-notifications');
        if (!container) return;

        // Rate limiting for notifications
        const now = Date.now();
        if (window.lastNotificationTime && (now - window.lastNotificationTime) < 500) {
            return; // Too frequent notifications
        }
        window.lastNotificationTime = now;

        // Validate and sanitize message
        if (typeof message !== 'string' || message.length > 200) {
            message = 'Nieprawidłowa wiadomość';
        }

        const validTypes = ['success', 'error', 'warning', 'info'];
        if (!validTypes.includes(type)) {
            type = 'info';
        }

        const notification = document.createElement('div');
        notification.className = `mb-2 p-3 rounded-md shadow-lg max-w-sm transition-all ${
            type === 'success' ? 'bg-green-50 text-green-800 border border-green-200' :
            type === 'error' ? 'bg-red-50 text-red-800 border border-red-200' :
            type === 'warning' ? 'bg-yellow-50 text-yellow-800 border border-yellow-200' :
            'bg-blue-50 text-blue-800 border border-blue-200'
        }`;
        
        // Create notification content securely
        const messageSpan = document.createElement('span');
        messageSpan.textContent = message; // Safe text insertion
        
        const closeButton = document.createElement('button');
        closeButton.textContent = '×';
        closeButton.className = 'ml-2 text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-400 rounded';
        closeButton.onclick = function() {
            if (this.parentElement && this.parentElement.parentElement) {
                this.parentElement.parentElement.remove();
            }
        };
        
        const flexDiv = document.createElement('div');
        flexDiv.className = 'flex justify-between items-center';
        flexDiv.appendChild(messageSpan);
        flexDiv.appendChild(closeButton);
        
        notification.appendChild(flexDiv);
        container.appendChild(notification);
        
        // Auto-remove after timeout
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 5000);
    }

    // Enhanced sortable initialization with security
    function initializeChildrenSortable() {
        const container = document.getElementById('children-container');
        if (!container) return;

        // Verify CSRF token exists
        const csrfToken = container.dataset.csrfToken;
        if (!csrfToken) {
            console.warn('Security: CSRF token missing for sortable container');
            return;
        }

        // Clean up previous instance
        if (container.sortableInstance) {
            container.sortableInstance.destroy();
        }

        // Rate limiting for sort operations
        let lastSortTime = 0;
        const SORT_RATE_LIMIT = 1000; // 1 second between sort operations

        container.sortableInstance = Sortable.create(container, {
            handle: '.drag-handle',
            draggable: 'li[data-child-id]',
            ghostClass: 'opacity-50',
            chosenClass: 'bg-blue-100',
            animation: 150,
            onEnd: function (evt) {
                const now = Date.now();
                if (now - lastSortTime < SORT_RATE_LIMIT) {
                    console.warn('Security: Sort operation rate limited');
                    return;
                }
                lastSortTime = now;

                // Validate and sanitize child IDs
                const childElements = container.querySelectorAll('li[data-child-id]');
                const childIds = Array.from(childElements)
                    .map(li => {
                        const childId = li.getAttribute('data-child-id');
                        // Validate that childId is a positive integer
                        const parsedId = parseInt(childId, 10);
                        return (parsedId > 0 && parsedId.toString() === childId) ? parsedId : null;
                    })
                    .filter(id => id !== null);

                if (childIds.length === 0) {
                    console.warn('Security: No valid child IDs found for sorting');
                    return;
                }

                // Find Livewire component securely
                const wireElement = container.closest('[wire\\:id]');
                if (!wireElement) {
                    console.warn('Security: Livewire component not found');
                    return;
                }

                const wireId = wireElement.getAttribute('wire:id');
                if (!wireId) {
                    console.warn('Security: Livewire ID not found');
                    return;
                }

                try {
                    const livewireComponent = Livewire.find(wireId);
                    if (livewireComponent) {
                        livewireComponent.call('updateChildrenOrder', childIds);
                    }
                } catch (error) {
                    console.error('Security: Error updating children order:', error);
                    showChildrenNotification('Błąd podczas zmiany kolejności elementów', 'error');
                }
            }
        });
    }

    // Security: Disable context menu on sensitive elements (optional)
    document.addEventListener('DOMContentLoaded', function() {
        const sensitiveElements = document.querySelectorAll('.security-protected-component *');
        sensitiveElements.forEach(element => {
            element.addEventListener('contextmenu', function(e) {
                // Don't prevent - accessibility concern, just log
                console.info('Context menu accessed on protected element');
            });
        });
    });
    </script>
    @endpush
</div>
