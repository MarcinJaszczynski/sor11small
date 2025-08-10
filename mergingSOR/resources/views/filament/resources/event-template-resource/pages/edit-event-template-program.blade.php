<x-filament-panels::page>
    @livewireStyles
    
    <div>
        <livewire:event-program-tree-editor :eventTemplate="$eventTemplate" />
    </div>
    
    @livewireScripts
</x-filament-panels::page>