<span>Compartilhe</span>
<ul class="share">
    <li>
        <a href="http://www.facebook.com/share.php?u={{ \URL::current() }}&title=@yield('title'){{ $websiteSettings['title'] }}" target="_blank" title="Compartilhar no Facebook">
            <span class="fa fa-facebook"></span>
        </a>
    </li>
    <li>
        <a href="https://twitter.com/intent/tweet?original_referer={{ \URL::current() }}&ref_src=twsrc%5Etfw&text=@yield('title'){{ $websiteSettings['title'] }}&tw_p=tweetbutton&url={{ \URL::current() }}" target="_blank" title="Compartilhar no Twitter">
            <span class="fa fa-twitter"></span>
        </a>
    </li>
    <li>
        <a href="http://www.linkedin.com/shareArticle?mini=true&url={{ \URL::current() }}&title=@yield('title'){{ $websiteSettings['title'] }}" target="_blank" title="Compartilhar no LinkedIn">
            <span class="fa fa-linkedin"></span>
        </a>
    </li>
    <li>
        <a href="mailto:?subject=@yield('title'){{ $websiteSettings['title'] }}&body=Olá,%0D%0A%0D%0AEstive acessando o website {{ $websiteSettings['title'] }} e gostaria de te indicar este link:%0D%0A%0D%0A @yield('title'){{ $websiteSettings['title'] }}%0D%0A{{ \URL::current() }}"><span class="fa fa-envelope-o"></span></a>
    </li>
</ul>