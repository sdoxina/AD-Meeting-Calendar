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
    <section class="flex flex-col justify-center items-center w-full h-[95dvh] max-h-[1080px] hero">
        <h2 class="font-black text-4xl text-center">
            A Simple and Efficient Tool for Scheduling Meetings
        </h2>
        <h2 class="font-black text-gray-500 text-2xl text-center mt-4">
            Plan, view, and manage meetings with ease. Stay organized and connected with your team.
        </h2>
    </section>

    <!-- Feature Section -->
    <section class="flex justify-center my-24 w-full">
        <div class="gap-4 grid grid-cols-3 grid-flow-row-dense container Features">
            <?php foreach ($featuresList as $value): ?>
                <div class="flex flex-col gap-4 p-4 pb-14 border rounded-lg">
                    <img src="<?php echo $value['image'] ?>" alt=""
                        class="bg-gray-400 rounded-md object-cover aspect-square">
                    <h3 class="font-bold text-xl"><?php echo $value["title"] ?></h3>
                    <p class="font-semibold text-gray-500">
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
    <section class="flex flex-col justify-center items-center my-42 w-full max-h-[200px]">
        <h3 class="font-black text-4xl text-center">Get Started – Plan Your First Meeting</h3>
        <p class="text-gray-500 text-xl text-center mt-2">Try the demo and experience effortless meeting scheduling</p>
    </section>
    <?php
    },
    $title
);
?>
