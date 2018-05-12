<?php

// Include utility functions
require '_funcs.php';

// Include the script elements
require '_scripts.php';

// Phrase indices
$imageIndex = rand(1, 7);
$titleIndex = 0;
$leadInIndex = 0;
$jobIndex = 0;
$nameIndex = 0;
$storyIndex = 0;
$motivationIndex = 0;
$leadOutIndex = 0;

// Is the user asking for a particular combination?
$urlChars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
$urlCharsArray = str_split($urlChars);

$existing = false;

if (preg_match('#^/([a-zA-Z]{7})$#', $_SERVER['REQUEST_URI'], $params)) {
    // Looks like we got a match...
    $id = $params[1];

    $titleIndex = array_search($id{0}, $urlCharsArray);
    $leadInIndex = array_search($id{1}, $urlCharsArray);
    $jobIndex = array_search($id{2}, $urlCharsArray);
    $nameIndex = array_search($id{3}, $urlCharsArray);
    $storyIndex = array_search($id{4}, $urlCharsArray);
    $motivationIndex = array_search($id{5}, $urlCharsArray);
    $leadOutIndex = array_search($id{6}, $urlCharsArray);

    $existing = $titleIndex < count($titles) &&
                $leadInIndex < count($leadIns) &&
                $jobIndex < count($jobs) &&
                $nameIndex < count($names) &&
                $storyIndex < count($stories) &&
                $motivationIndex < count($motivations) &&
                $leadOutIndex < count($leadOuts);
}

if (!$existing) {
    // Pick 'em at random
    $titleIndex = rand(0, count($titles)-1);
    $leadInIndex = rand(0, count($leadIns)-1);
    $jobIndex = rand(0, count($jobs)-1);
    $nameIndex = rand(0, count($names)-1);
    $storyIndex = rand(0, count($stories)-1);
    $motivationIndex = rand(0, count($motivations)-1);
    $leadOutIndex = rand(0, count($leadOuts)-1);
}

// Additional variable grammar...
$noName = empty($names[$nameIndex]);
$needsAnA = $noName && (startsWith($names[$nameIndex], 'A ') || startsWith($names[$nameIndex], 'Once') || startsWith($names[$nameIndex], 'Famous'));

// Build the full "script"
$movieTitle = getString($titles, $titleIndex);

$moviePlot = ($needsAnA) ? 'A ' : '';
$moviePlot .= getString($leadIns, $leadInIndex, $needsAnA) . ' ';
$moviePlot .= getString($jobs, $jobIndex) . ' ';
$moviePlot .= getString($names, $nameIndex) . ' ';
$moviePlot .= getString($stories, $storyIndex) . ' ';
$moviePlot .= getString($motivations, $motivationIndex) . ' ';
$moviePlot .= getString($leadOuts, $leadOutIndex);

// Generate the URL for this film
$uniqueUrl = '';
$uniqueUrl .= $urlChars{$titleIndex};
$uniqueUrl .= $urlChars{$leadInIndex};
$uniqueUrl .= $urlChars{$jobIndex};
$uniqueUrl .= $urlChars{$nameIndex};
$uniqueUrl .= $urlChars{$storyIndex};
$uniqueUrl .= $urlChars{$motivationIndex};
$uniqueUrl .= $urlChars{$leadOutIndex};

// Share message for Twitter
$shareMessage = 'Jason Statham stars in ' . strtoupper($movieTitle) . ': ' . truncateString($moviePlot, 65, true) . ' http://' . $_SERVER['HTTP_HOST'] . '/' . $uniqueUrl . ' #stathamator';

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />

        <meta name="author" content="Daniel G Wood" />
        <meta name="copyright" content="&copy;2017 Daniel G Wood" />

        <meta name="description" content="<?php echo $moviePlot; ?>">
        <meta property="og:title" content="<?php echo $movieTitle; ?> - The Stathamator">
        <meta property="og:description" content="<?php echo $moviePlot; ?>">
        <meta itemprop="name" content="<?php echo $movieTitle; ?> - The Stathamator">
        <meta itemprop="description" content="<?php echo $moviePlot; ?>">

        <meta property="og:email" content="daniel.g.wood@gmail.com" />
        <meta property="og:type" content="website" />
        <meta property="og:image" content="assets/statham<?php echo $imageIndex; ?>.jpg" />
        <meta property="og:site_name" content="stathamator.com" />
        <meta property="fb:admins" content="512686422" />
        <meta itemprop="image" content="assets/statham<?php echo $imageIndex; ?>.jpg" />

        <title><?php echo $movieTitle; ?> - The Stathamator</title>

        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inconsolata|Share:700">
        <link rel="stylesheet" href="assets/all.css" />

        <link rel="shortcut icon" href="assets/favicon.ico" type="image/x-icon" />
        <link rel="apple-touch-icon" href="assets/apple-touch-icon.png" />
    </head>
    <body>
        <header>
            <h1>The Stathamator</h1>
        </header>

        <section>
            <img class="stathe" src="assets/statham<?php echo $imageIndex; ?>.jpg" alt="Jason Statham" />

            <span class="starring">Jason Statham stars in</span>

            <h2><?php echo $movieTitle; ?></h2>

            <p><?php echo $moviePlot; ?></p>
        </section>

        <nav>
            <a class="button" href="/" title="Make another film"><img src="assets/refresh.png" alt="" /></a>
            <a class="button" href="https://twitter.com/intent/tweet?text=<?php echo urlencode($shareMessage); ?>" target="_blank" title="Tweet this!"><img src="assets/twitter.png" alt="" /></a>
            <a class="button" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('http://' . $_SERVER['HTTP_HOST'] . '/' . $uniqueUrl); ?>" target="_blank" title="Share on Facebook"><img src="assets/facebook.png" alt="" /></a>
        </nav>

        <footer>
            <small>Made by <a href="https://twitter.com/danielgwood">@danielgwood</a> whilst watching <em>a lot</em> of Statham films. Icons by <a rel="nofollow" href="http://simpleicon.com/" target="_blank">SimpleIcon</a> (CC BY 3.0).</small>
        </footer>

        <!-- JS -->
        <script> history.replaceState(null, null, '<?php echo $uniqueUrl; ?>'); </script>

        <!-- Google Analytics -->
        <script>
          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

          ga('create', 'UA-15405999-3', 'auto');
          ga('send', 'pageview');

        </script>
    </body>
</html>