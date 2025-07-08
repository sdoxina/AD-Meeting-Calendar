<?php
// call the layout you want to use from layout folder
require_once LAYOUTS_PATH . "/main.layout.php";
$mongoCheckerResult = require_once HANDLERS_PATH . "/mongodbChecker.handler.php";
$postgresqlCheckerResult = require_once HANDLERS_PATH . "/postgreChecker.handler.php";

$title = "Meeting Calendar";

// functions that will render the layout of your choosing
renderMainLayout(
    function () use ($mongoCheckerResult, $postgresqlCheckerResult) {
        // Data for features
        require_once STATICDATAS_PATH . "/feature.staticData.php";
        ?>
    <!-- Hero Section -->
    <section class="hero">
    <div class="hero-content">
        <h1 class="hero-title">A Simple and Efficient Tool for Scheduling Meetings</h1>
        <p class="hero-subtitle">
        Plan, view, and manage meetings with ease. Stay organized and connected with your team.
        </p>
    </div>
    </section>

    <!-- Features Section -->
    <section class="features">
    <div class="features-grid">
        <?php foreach ($featuresList as $value): ?>
        <div class="feature-card">
            <img src="<?php echo $value['image'] ?>" alt="" class="feature-image">
            <h3 class="feature-title"><?php echo $value["title"] ?></h3>
            <p class="feature-description">
            <?php echo $value['description'] ?>
            <br>
            <?php if ($value["title"] == "Dockerized Workflow.") {
                echo $mongoCheckerResult;
                echo $postgresqlCheckerResult;
            } ?>
            </p>
        </div>
        <?php endforeach; ?>
    </div>
    </section>

    <!-- Call to Action -->
    <section class="cta">
    <h2 class="cta-title">Get Started – Plan Your First Meeting</h2>
    <p class="cta-subtitle">Try the demo and experience effortless meeting scheduling</p>
    </section>
    <?php
    },
    $title,
    [
        "css" => [
            "./assets/css/style.css"
        ],
    ]
);
?>
