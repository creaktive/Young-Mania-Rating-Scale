<?php
session_start();

// Define all questions and their options using the revised, consistent structure
// Options are 0-indexed for display, and 'weights' maps these indices to actual YMRS scores.
$questions = [
    1 => [
        'title' => 'Elevated Mood',
        'options' => [
            'Absent',
            'Mildly or possibly increased on questioning',
            'Definite subjective elevation; optimistic, self-confident; cheerful; appropriate to content',
            'Elevated; inappropriate to content; humorous',
            'Euphoric; inappropriate laughter; singing'
        ],
        'weights' => [0, 1, 2, 3, 4]
    ],
    2 => [
        'title' => 'Increased Motor Activity-Energy',
        'options' => [
            'Absent',
            'Subjectively increased',
            'Animated; gestures increased',
            'Excessive energy; hyperactive at times; restless (can be calmed)',
            'Motor excitement; continuous hyperactivity (cannot be calmed)'
        ],
        'weights' => [0, 1, 2, 3, 4]
    ],
    3 => [
        'title' => 'Sexual Interest',
        'options' => [
            'Normal; not increased',
            'Mildly or possibly increased',
            'Definite subjective increase on questioning',
            'Spontaneous sexual content; elaborates on sexual matters; hypersexual by self-report',
            'Overt sexual acts (toward patients, staff, or interviewer)'
        ],
        'weights' => [0, 1, 2, 3, 4]
    ],
    4 => [
        'title' => 'Sleep',
        'options' => [
            'Reports no decrease in sleep',
            'Sleeping less than normal amount by up to one hour',
            'Sleeping less than normal by more than one hour',
            'Reports decreased need for sleep',
            'Denies need for sleep'
        ],
        'weights' => [0, 1, 2, 3, 4]
    ],
    5 => [
        'title' => 'Irritability',
        'options' => [
            'Absent',
            'Subjectively increased',
            'Irritable at times during interview; recent episodes of anger or annoyance on ward',
            'Frequently irritable during interview; short, curt throughout',
            'Hostile, uncooperative; interview impossible'
        ],
        'weights' => [0, 2, 4, 6, 8] // Specific YMRS scores
    ],
    6 => [
        'title' => 'Speech (Rate and Amount)',
        'options' => [
            'No increase',
            'Feels talkative',
            'Increased rate or amount at times, verbose at times',
            'Push; consistently increased rate and amount; difficult to interrupt',
            'Pressured; uninterruptible, continuous speech'
        ],
        'weights' => [0, 2, 4, 6, 8] // Specific YMRS scores
    ],
    7 => [
        'title' => 'Language-Thought Disorder',
        'options' => [
            'Absent',
            'Circumstantial; mild distractibility; quick thoughts',
            'Distractible, loses goal of thought; changes topics frequently; racing thoughts',
            'Flight of ideas; tangentiality; difficult to follow; rhyming, echolalia',
            'Incoherent; communication impossible'
        ],
        'weights' => [0, 1, 2, 3, 4]
    ],
    8 => [
        'title' => 'Content',
        'options' => [
            'Normal',
            'Questionable plans, new interests',
            'Special project(s); hyper-religious',
            'Grandiose or paranoid ideas; ideas of reference',
            'Delusions; hallucinations'
        ],
        'weights' => [0, 2, 4, 6, 8] // Specific YMRS scores
    ],
    9 => [
        'title' => 'Disruptive-Aggressive Behavior',
        'options' => [
            'Absent, cooperative',
            'Sarcastic; loud at times, guarded',
            'Demanding; threats on ward',
            'Threatens interviewer; shouting; interview difficult',
            'Assaultive; destructive; interview impossible'
        ],
        'weights' => [0, 2, 4, 6, 8] // Specific YMRS scores
    ],
    10 => [
        'title' => 'Appearance',
        'options' => [
            'Appropriate dress and grooming',
            'Minimally unkempt',
            'Poorly groomed; moderately disheveled; overdressed',
            'Disheveled; partly clothed; garish make-up',
            'Completely unkempt; decorated; bizarre garb'
        ],
        'weights' => [0, 1, 2, 3, 4]
    ],
    11 => [
        'title' => 'Insight',
        'options' => [
            'Present; admits illness; agrees with need for treatment',
            'Possibly ill',
            'Admits behavior change, but denies illness',
            'Admits possible change in behavior, but denies illness',
            'Denies any behavior change'
        ],
        'weights' => [0, 1, 2, 3, 4]
    ]
];

// Ensure session variables are initialized
if (!isset($_SESSION['answers'])) {
    $_SESSION['answers'] = [];
}
if (!isset($_SESSION['current_question'])) {
    $_SESSION['current_question'] = 1;
}

$current_q_num = $_SESSION['current_question'];
$total_questions = count($questions);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['answer'])) {
    $submitted_value = (int)$_POST['answer']; // This is the submitted score directly

    $_SESSION['answers'][$current_q_num] = $submitted_value;

    // Move to the next question
    $_SESSION['current_question']++;
    $current_q_num = $_SESSION['current_question'];

    // If all questions are answered, redirect to results
    if ($current_q_num > $total_questions) {
        header('Location: results.php');
        exit();
    }
}

// Display the current question
if ($current_q_num <= $total_questions) {
    $current_question_data = $questions[$current_q_num];
    $question_title = $current_question_data['title'];
    $question_options = $current_question_data['options'];
    $question_weights = $current_question_data['weights'];

    // Determine min/max/step for slider based on weights
    $min_score = min($question_weights);
    $max_score = max($question_weights);
    $step_score = 0.5; // Default step for continuous mode, will be overridden for discrete
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Question <?php echo $current_q_num; ?></title>
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; }
            .container { max-width: 800px; margin: auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px; }
            h2 { color: #333; }
            .question-text { font-size: 1.2em; margin-bottom: 20px; }
            .slider-container { margin-bottom: 20px; }
            .slider-label { margin-bottom: 10px; font-weight: bold; }
            .slider-value { display: inline-block; min-width: 30px; text-align: center; margin-left: 10px; }
            input[type="range"] { width: 100%; }
            .option-list ul { list-style: none; padding: 0; }
            .option-list li { margin-bottom: 5px; }
            .option-list label { display: block; margin-bottom: 10px; cursor: pointer; }
            .option-list input[type="radio"] { margin-right: 10px; }
            .submit-button {
                display: inline-block;
                padding: 10px 20px;
                background-color: #28a745;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                margin-top: 20px;
            }
            .submit-button:hover { background-color: #218838; }
            .progress { margin-top: 20px; font-size: 0.9em; color: #666; }
            .mode-toggle { margin-bottom: 20px; }
        </style>
    </head>
    <body>
        <div class="container">
            <h2>Question <?php echo $current_q_num; ?> of <?php echo $total_questions; ?></h2>
            <p class="question-text"><?php echo $question_title; ?></p>

            <div class="mode-toggle">
                <label>
                    <input type="checkbox" id="discreteModeToggle">
                    Simplified score
                </label>
            </div>

            <form method="POST" action="question.php">
                <div id="continuousMode" class="slider-container">
                    <div class="option-list">
                        <ul>
                            <?php foreach ($question_options as $index => $option_text): ?>
                                <li>
                                    (<?php echo $question_weights[$index]; ?>)
                                    <?php echo $option_text; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="slider-label">
                        Selected Score: <span id="sliderValue"><?php echo $min_score; ?></span>
                    </div>
                    <input type="range" id="scoreSlider" name="answer"
                           min="<?php echo $min_score; ?>"
                           max="<?php echo $max_score; ?>"
                           step="<?php echo $step_score; ?>"
                           value="<?php echo $min_score; ?>"
                           oninput="document.getElementById('sliderValue').innerText = this.value;"
                           required>
                </div>

                <div id="discreteMode" class="option-list" style="display: none;">
                    <?php foreach ($question_options as $index => $option_text): ?>
                        <label>
                            <input type="radio" name="answer_discrete" value="<?php echo $question_weights[$index]; ?>" required>
                            (<?php echo $question_weights[$index]; ?>)
                            <?php echo $option_text; ?>
                        </label>
                    <?php endforeach; ?>
                </div>

                <button type="submit" class="submit-button">Next Question</button>
            </form>
            <p class="progress">Progress: <?php echo floor(($current_q_num - 1) / $total_questions * 100); ?>%</p>
        </div>

        <script>
            const discreteModeToggle = document.getElementById('discreteModeToggle');
            const continuousModeDiv = document.getElementById('continuousMode');
            const discreteModeDiv = document.getElementById('discreteMode');
            const scoreSlider = document.getElementById('scoreSlider');
            const sliderValueSpan = document.getElementById('sliderValue');
            const discreteRadios = document.querySelectorAll('#discreteMode input[type="radio"]');

            function applyMode(isDiscrete) {
                if (isDiscrete) {
                    continuousModeDiv.style.display = 'none';
                    discreteModeDiv.style.display = 'block';
                    // Ensure the 'name' attribute is set correctly for submission
                    scoreSlider.removeAttribute('name');
                    discreteRadios.forEach(radio => radio.setAttribute('name', 'answer'));
                } else {
                    continuousModeDiv.style.display = 'block';
                    discreteModeDiv.style.display = 'none';
                    // Ensure the 'name' attribute is set correctly for submission
                    scoreSlider.setAttribute('name', 'answer');
                    discreteRadios.forEach(radio => radio.removeAttribute('name'));
                }
                localStorage.setItem('discreteMode', isDiscrete);
            }

            // Load saved state
            const savedMode = localStorage.getItem('discreteMode');
            const initialDiscrete = savedMode === 'true'; // Convert string to boolean
            discreteModeToggle.checked = initialDiscrete;
            applyMode(initialDiscrete);

            // Toggle functionality
            discreteModeToggle.addEventListener('change', function() {
                applyMode(this.checked);
            });

            // Update slider details for discrete mode if enabled
            function updateSliderForDiscrete() {
                // Get unique sorted weights for discrete snapping
                const uniqueWeights = [...new Set(<?php echo json_encode($question_weights); ?>)].sort((a, b) => a - b);

                if (discreteModeToggle.checked) {
                    scoreSlider.min = uniqueWeights[0];
                    scoreSlider.max = uniqueWeights[uniqueWeights.length - 1];
                    scoreSlider.step = 0.5; // We'll handle snapping in oninput

                    scoreSlider.oninput = function() {
                        const val = parseInt(this.value);
                        let closest = uniqueWeights[0];
                        let minDiff = Math.abs(val - closest);

                        for (let i = 1; i < uniqueWeights.length; i++) {
                            const diff = Math.abs(val - uniqueWeights[i]);
                            if (diff < minDiff) {
                                minDiff = diff;
                                closest = uniqueWeights[i];
                            }
                        }
                        this.value = closest;
                        sliderValueSpan.innerText = closest;
                    };
                } else {
                    // Reset to original continuous behavior
                    scoreSlider.min = <?php echo $min_score; ?>;
                    scoreSlider.max = <?php echo $max_score; ?>;
                    scoreSlider.step = <?php echo $step_score; ?>;
                    scoreSlider.oninput = function() {
                        sliderValueSpan.innerText = this.value;
                    };
                }
                // Ensure initial value is set correctly on mode change
                if(discreteModeToggle.checked) {
                    scoreSlider.value = uniqueWeights[0];
                    sliderValueSpan.innerText = uniqueWeights[0];
                } else {
                    scoreSlider.value = <?php echo $min_score; ?>;
                    sliderValueSpan.innerText = <?php echo $min_score; ?>;
                }
            }

            // Initial setup for slider based on mode
            updateSliderForDiscrete();
            discreteModeToggle.addEventListener('change', updateSliderForDiscrete);

            // For discrete radio buttons, ensure one is selected by default if in discrete mode
            if (initialDiscrete) {
                if (discreteRadios.length > 0) {
                    discreteRadios[0].checked = true;
                }
            }
        </script>
    </body>
    </html>
    <?php
}
?>
