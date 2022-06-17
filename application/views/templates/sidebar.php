<?php
    $submenus = [
        [
            'id'    => 1,
            'url'   => '',
            'judul' => 'All Posts'
        ],
        [
            'id'    => 2,
            'url'   => 'addnew',
            'judul' => 'Add New'
        ],
        [
            'id'    => 3,
            'url'   => 'preview',
            'judul' => 'Preview'
        ],
    ];
?>
<div class="sidebar">
    <nav class="sidebar-nav">
        <ul class="nav">
            <li class="nav-item nav-dropdown open">
                <a class="nav-link nav-dropdown-toggle active" href="#"><i class="nav-icon icon-list"></i> Posts</a>
                <ul class="nav-dropdown-items">
                    <?php foreach($submenus as $submenu) : ?>
                        <li class="nav-item">
                            <a class="nav-link <?= ($idMenu == $submenu['id'])? 'active' : ''; ?>" href="<?= base_url('posts/'.$submenu['url']);?>">
                                <i class="nav-icon icon-check"></i> <?= $submenu['judul']; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </li>
        </ul>
    </nav>
    <button class="sidebar-minimizer brand-minimizer" type="button"></button>
</div>