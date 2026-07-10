<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 rounded-md font-semibold text-xs uppercase tracking-widest transition ease-in-out duration-150']) }}
        style="background: rgb(138, 28, 36); color: rgb(251, 248, 243); border: none; {{ $attributes->get('style', '') }}"
        onmouseover="this.style.background='rgb(110, 20, 27)'" onmouseout="this.style.background='rgb(138, 28, 36)'">
    {{ $slot }}
</button>
