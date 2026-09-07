<?php
namespace TV\Core;
class View {
    public static function render(string $title,string $content,array $opts=[]): void {
        $user=Auth::user();$flashes=Helpers::flashes();$active=$opts['active']??'';$settings=$GLOBALS['settings'];
        require TV_ROOT.'/app/Views/layouts/header.php';
        echo $content;
        require TV_ROOT.'/app/Views/layouts/footer.php';
    }
}
