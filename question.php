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
    // Save the answer for the current question
    $submitted_option_index = (int)$_POST['answer']; // This is the 0-based index
    $current_q_data = $questions[$current_q_num];

    // Get the actual score from the weights array using the submitted index
    $score = $current_q_data['weights'][$submitted_option_index];

    $_SESSION['answers'][$current_q_num] = $score;

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
    $question_weights = $current_question_data['weights']; // Used for displaying the score hint

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
        </style>
    </head>
    <body>
        <div class="container">
            <h2>Question <?php echo $current_q_num; ?> of <?php echo $total_questions; ?></h2>
            <p class="question-text"><?php echo $question_title; ?></p>
            <form method="POST" action="question.php">
                <div class="option-list">
                    <?php foreach ($question_options as $index => $option_text): ?>
                        <label>
                            <input type="radio" name="answer" value="<?php echo $index; ?>" required>
                            (<?php echo $question_weights[$index]; ?>)
                            <?php echo $option_text; ?>
                        </label>
                    <?php endforeach; ?>
                </div>
                <button type="submit" class="submit-button">Next Question</button>
            </form>
            <p class="progress">Progress: <?php echo floor(($current_q_num - 1) / $total_questions * 100); ?>%</p>
        </div>
    </body>
    </html>
    <?php
}
?>
