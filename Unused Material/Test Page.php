
<title> Test </title>
<link rel = "stylesheet" type = "text/css" href = "../CSS/Test Page.css"></link>

<!--'test_status' is the ID for header 1. JavaScript code later on will be used to retrieve the header by ID and change the contents.-->
<h1 id="test_status">Chinese Mandarin Test</h1>

<?php
// The characters that will be used for the questions are stored in their own variables in order for them to be
// easily added into and recognised throughout the code. The variables can be put into a single line, but it's easier to read when all of the
// variables have values set.
$ni = json_decode('"\u4F60"');
$nin = json_decode('"\u60A8"');
$hao = json_decode('"\u597D"');
$wo = json_decode('"\u6211"');
$hen = json_decode('"\u5F88"');
$ma = json_decode('"\u5417"');

//'questionsAnswered' will represent the number of questions that have been answered and it will be used as an index to
// locate the values in the 'questionOrder' array for the variable 'questionOrderIndex' to use.
$questionsAnswered = 0;

//'questionOrderIndex' will represent the index of the question order which will be used to locate the next question for the test.
// The question list that contains the questions are not randomised, but the values of 'questionOrder' will be randomised meaning
// that the questions will be displayed in a random order.
$questionOrderIndex = 0;

// 'total' will represent the total number of questions that have been asked.
$total = 0;
$correct = 0;

// 'questionSet1' stores the first type of questions that the user can receive in the test.
// This is a 2-D array because it's an array that will hold other arrays that
// will have the question and possible answers. The questions require the user to translate sentences.
$questionSet1 = array(
    array("Translate: " . $ni . $hao, array("hello", "hi")),
    array("Translate: " . $ni . $hao . $ma . "?", array("how are you?", "how are you")),
    array("Translate: " . $wo . $hen . $hao, array("i'm good", "i'm fine", "i am good", "i am fine"))
);

// 'questionSet2' stores the second type of questions that the user can receive. This is also a 2-D array
// with questions that require the user to translate single characters.
$questionSet2 = array(
    array("Translate: " . $ni, array("you")),
    array("Translate: " . $nin, array("you (polite version)", "you")),
    array("Translate: " . $hao, array("good, fine", "good", "fine")),
	array("Translate: " . $wo, array("i, me", "i", "me")),
	array("Translate: " . $hen, array("very")),
	array("Translate: " . $ma, array("interrogative word (used at the end of a statement to form a question)", "interrogative word"))
);

// 'questionSet3' stores the third type of questions that the user can receive. This is also a 2-D array with questions that require the user to select
// a character or word matching with the word or charcter that is displayed. The last element of each question array holds the letter to the answer.
$questionSet3 = array(
	array("Select the word matching: " . $ni, array("You (Polite Version)", "You", "I, me", "Very", "Interrogative word (used at the end of a statement to form a question)", "Good, fine"), "You"),
	array("Select the word matching: " . $nin, array("I, me", "Interrogative word (used at the end of a statement to form a question)", "You", "You (Polite Version)", "Good, fine", "Very"), "You (Polite Version)"),
    array("Select the word matching: " . $hao, array("You (Polite Version)", "Very", "Good, fine", "I, me", "Interrogative word (used at the end of a statement to form a question)", "You"), "Good, fine"),
	array("Select the word matching: " . $wo, array("Very", "You", "Good, fine", "Interrogative word (used at the end of a statement to form a question)", "I, me", "You (Polite Version)"), "I, me"),
	array("Select the word matching: " . $hen, array("Very", "Good, fine", "Interrogative word (used at the end of a statement to form a question)", "You", "I, me", "You (Polite Version)"), "Very"),
	array("Select the word matching: " . $ma, array("You (Polite Version)", "Good, fine", "You", "I, me", "Very", "Interrogative word (used at the end of a statement to form a question)"), "Interrogative word (used at the end of a statement to form a question)"),
    array("Select the character matching: You", array($wo, $ni, $nin, $hao, $ma, $hen), $ni),
	array("Select the character matching: You (Polite Version)", array($nin, $hen, $wo, $ma, $ni, $hao), $nin),
	array("Select the character matching: Good, fine", array($ma, $hao, $nin, $ni, $hen, $wo), $hao),
	array("Select the character matching: I, me", array($hen, $nin, $ni, $hao, $ma, $wo), $wo),
	array("Select the character matching: Very", array($hao, $wo, $ma, $hen, $ni, $nin), $hen),
	array("Select the character matching: Interrogative word (used at the end of a statement to form a question)", array($ni, $hao, $ma, $hen, $wo, $nin), $ma)
);

// This is a blank array that will contain the questions that the user will receive for the test.
$questions = array();
define("NUM_OF_QUESTIONS", 3);

// This 'FOR' loop will add the questions from the question lists into the 'questions' array until there are 10 items (questions) in the array.
// 'i' will represent the question number for each list. The maximum value that 'i' will be is 2 because there are only 3 questions minimum from 'questionSet1'.
for ($i = 0; $i < NUM_OF_QUESTIONS; $i++) {

	// This will only apply if there are 9 questions in the 'questions' array.
	if (count($questions) === 9) {
		// A question from 'questionSet2' will be added to the 'questions' array, but this means that
		// there will be more questions from 'questionSet2' than any of the other questions.
		array_push($questions, $questionSet2[$i]);

	// If there are less than 9 questions in total, one question from each of teh question sets will be added into the 'questions' array.
	} else {
		array_push($questions, $questionSet1[$i]);
		array_push($questions, $questionSet2[$i]);
		array_push($questions, $questionSet3[$i]);
	}
}

// Display the current question.
function renderQuestion() {

	global $questionOrderIndex;
	global $questionsAnswered;
	global $questions;
	global $correct;
	global $total;
	
	// Get the current question.
	$question = $questions[$questionOrderIndex][0];
	
	ob_start();
	echo "<div id = 'test'>";
	echo "Question " . ($questionsAnswered + 1) . " of " . NUM_OF_QUESTIONS;
	
	// This code will run only if the user has answered at least one question. The percentage of questions the user has currently got correct in the test
	// will be added into the 'test_status' header and displayed under the current contents of the header.
	if ($total !== 0) {
		echo "<br>Currently: " . ($correct / $total * 100) . "% correct";
	}
	
	//The question is displayed.
	echo "<h3>" . $question . "</h3>";
	
	// This checks if specific words are in the question. For this case it's 'Select the' which will mean that the question will require a selection for the answer.
	if (strpos($question, "Select the") !== false) {
		// Adds each answer options (for the question) to the page.
		for ($i = 0; $i < count($questions[$questionOrderIndex][1]); $i++) {
			$radioBox = $questions[$questionOrderIndex][1][$i];
			echo "<label><input type='radio' name='choices' value='" . $radioBox . "'>" . $radioBox . "</label><br>";
		}

		// This displays the submit button and when it's clicked on, the 'checkSelectAnswer()' function will run.
	    echo "<br><button onclick='checkSelectAnswer()'> Submit Answer </button>";

	// This applies if the question has the text 'Translate:'.
	} else if (strpos($question, "Translate:") !== false) {
		// This displays a textbox that allows the user to store the value the user types in. It has the ID 'Translate'.
		echo "<form method = 'post'>
			  <input type='text' name='txtTranslate'></input><br></br>
			  <input type='submit' name = 'btnTranslate' value = 'Submit Answer'></input>
			  </form>";
	}

	echo "</div>";
}

// Checks the user's answer for a translate question.
function checkTranslateAnswer() {
	global $questions;
	global $correct;
	$translateAnswer = strtolower($_POST["txtTranslate"]);
	
	// A regex allowing spaces, apostrophes, lowercase and uppercase letters.
	$lettersSpaces = "/^[a-zA-Z-' ]+$/";

	// A regex that allows spaces or no inputs.
	$spacesOrNoInput = "/^ *$/";
	
	if (in_array($translateAnswer, $questions[$questionOrderIndex][1])) {
		ob_end_clean();
		echo "<script> alert('Correct') </script>";
		$correct++;
	} else if (preg_match($spacesOrNoInput, $translateAnswer)) {
		echo "Enter an answer";
		return false;
	} else if (!preg_match($lettersSpaces, $translateAnswer)) {
		echo "Enter letters only.";
		return false;
	} else {
		ob_end_clean();
		echo $questionOrderIndex;
		echo $questionsAnswered;
		echo "<script> alert('Incorrect') </script>";
	}
	
	$questionOrderIndex++;
	$questionsAnswered++;
	progressNextQuestion();
}

// Determines the progress based on the number of questions answered.
function progressNextQuestion() {
	global $questionsAnswered;
	
	// Show the next question if all questions haven't been answered.
	if ($questionsAnswered < NUM_OF_QUESTIONS) {
		renderQuestion();
	// Show the results when all questions have been answered.
	} else {
		echo "<script> alert('Results') </script>";
	}
}

progressNextQuestion();
if (array_key_exists("btnTranslate", $_POST)) {
	checkTranslateAnswer();
}
?>
<!--This is the back button for the test page which is given a class of 'TestBack'.-->
<br><input class="TestBack" onClick="window.location.href = '5) Assessment Page.html'" type="button" value="Quit Test"/>