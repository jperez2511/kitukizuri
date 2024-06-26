<x-guest-layout>

    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="content-page wide-md m-auto">
                    <div class="nk-block-head-content text-center">
                        <x-authentication-card-logo />
                    </div>
                    <div class="nk-block">
                        <div class="card card-bordered">
                            <div class="card-inner card-inner-xl">
                                <div class="entry mt-4">
                                    {!! $terms !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
