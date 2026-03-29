@props([
    'livewireId',
])

<span
    x-cloak
    x-show="selectedRecords.length"
    class="whitespace-nowrap text-sm font-medium text-gray-950 dark:text-white"
    x-init="
        let debounce;
        const label = @js(__('Total seleccionado'));
        const bind = (lw) => {
            const sync = () => {
                clearTimeout(debounce);
                debounce = setTimeout(() => {
                    if (! selectedRecords.length) {
                        $el.textContent = '';
                        return;
                    }
                    lw.call('sumSelectedTransactions', [...selectedRecords]).then((s) => {
                        $el.textContent = label + ': $' + s;
                    });
                }, 120);
            };
            $watch('selectedRecords', sync);
            sync();
        };
        const tryLw = (n = 0) => {
            const lw = Livewire.find(@js($livewireId));
            if (lw) {
                bind(lw);
                return;
            }
            if (n < 40) {
                setTimeout(() => tryLw(n + 1), 25);
            }
        };
        $nextTick(() => tryLw());
    "
>{{ __('Total seleccionado') }}: $0.00</span>
