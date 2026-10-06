<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-mm-navy border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-mm-navy-hover focus:outline-none focus:ring-2 focus:ring-mm-navy focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>