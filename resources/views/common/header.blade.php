<header class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <a class="navbar-brand" href="/">查价喵 <span class="badge badge-pill badge-info">Beta</span></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item{{Request::path() === '/' ? ' active' : ''}}">
                <a class="nav-link" href="/">主页</a>
            </li>
            <li class="nav-item{{Request::path() === 'about' ? ' active' : ''}}">
                <a class="nav-link" href="/about">关于</a>
            </li>
        </ul>
    </div>
</header>