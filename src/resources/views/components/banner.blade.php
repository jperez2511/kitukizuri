@props(['style' => session('flash.bannerStyle', 'success'), 'message' => session('flash.banner')])

<div
    x-data="{{ json_encode(['show' => true, 'style' => $style, 'message' => $message]) }}"
    class="alert alert-icon alert-dismissible"
    :class="{'alert-success': style == 'success', 'alert-danger': style == 'danger', 'alert-secondary': style != 'success' && style != 'danger'}"
    style="display: none;"
    x-show="show && message"
    x-on:banner-message.window="
        style = event.detail.style;
        message = event.detail.message;
        show = true;"
>
    <em x-show="style == 'success'" class="icon ni ni-check-circle"></em>
    <em x-show="style == 'danger'" class="icon ni ni-cross-circle"></em>
    <em x-show="style == 'warning'" class="icon ni ni-alert-circle"></em>
    
    hola mundo
    <button class="close" x-on:click="show = false" data-bs-dismiss="alert"></button>
</div>