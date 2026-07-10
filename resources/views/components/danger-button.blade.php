<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 rounded-md font-semibold text-xs uppercase tracking-widest transition ease-in-out duration-150']) }}
        style="background: rgba(180, 30, 30, 0.06); color: rgb(180, 30, 30); border: 1px solid rgba(180, 30, 30, 0.3); {{ $attributes->get('style', '') }}"
        onmouseover="this.style.background='rgba(180, 30, 30, 0.12)'" onmouseout="this.style.background='rgba(180, 30, 30, 0.06)'">
    {{ $slot }}
</button>
