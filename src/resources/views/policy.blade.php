<x-guest-layout>
    <div class="nk-block nk-block-middle wide-lg mx-auto">
        <div class="brand-logo pb-4 text-center">
            <x-authentication-card-logo />
        </div>

        <div class="card card-bordered">
            <div class="card-inner card-inner-lg">
                <div class="entry">
                    {!! $policy !!}
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
