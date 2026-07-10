<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 rounded-md font-semibold text-xs uppercase tracking-widest transition ease-in-out duration-150']) }}
        style="background: white; color: rgb(58, 71, 98); border: 1px solid rgba(27, 42, 74, 0.15); {{ $attributes->get('style', '') }}"
        onmouseover="this.style.background='rgba(27, 42, 74, 0.04)'" onmouseout="this.style.background='white'">
    {{ $slot }}
</button>
