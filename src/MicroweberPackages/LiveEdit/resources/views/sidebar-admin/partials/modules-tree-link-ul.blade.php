

<li class="nav-item">

    <div>

        @foreach($item->getChildren() as $subItem)

            <a href="">
                       <span>
                            {{ $subItem->getName()}}
                       </span>a

            </a>

        @endforeach

    </div>
</li>
