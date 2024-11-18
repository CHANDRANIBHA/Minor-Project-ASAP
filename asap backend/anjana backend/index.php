
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professional Training Session</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        label {
            font-weight: bold;
            margin-top: 10px;
            display: block;
        }
        select, input, textarea, button {
            width: 100%;
            margin-top: 5px;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        button {
            background-color: #5cb85c;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #4cae4c;
        }
        .checkbox-container {
            display: flex;
            flex-wrap: wrap;
        }
        .checkbox-container label {
            width: 45%;
            margin: 5px;
        }
        .error-message {
            color: red;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Professional Training Session</h1>
        <form id="trainingForm" method="POST" action="prof.php">
            <label for="activity">Select Activity</label>
            <select id="activity" name="activity" required>
                <option value="">-- Select --</option>
                <option value="Mock Interviews">Mock Interviews</option>
                <option value="Group Discussion">Group Discussion</option>
                <option value="Public Speaking">Public Speaking</option>
            </select>

            <label for="semester">Select Semester</label>
            <select id="semester" name="semester" required>
                <option value="">-- Select --</option>
                <option value="3">Semester 3</option>
                <option value="4">Semester 4</option>
                <option value="5">Semester 5</option>
            </select>

            <label for="classList">Available Classes</label>
            <div id="classList" class="checkbox-container">
                <label><input type="checkbox" name="classes[]" value="1"> Class 1</label>
                <label><input type="checkbox" name="classes[]" value="2"> Class 2</label>
            </div>

            <label for="studentCount">Number of Students (min: 5, max: 50)</label>
            <input type="number" id="studentCount" name="studentCount" min="5" max="50" required>

            <label for="sessionTime">Session Date & Time</label>
            <input type="datetime-local" id="sessionTime" name="sessionTime" required>

            <label for="sessionLink">Session Link</label>
            <input type="url" id="sessionLink" name="sessionLink">

            <label for="description">Optional Description</label>
            <textarea id="description" name="description"></textarea>

            <button type="submit">Submit</button>
        </form>
    </div>
    <script src="prof.js"></script>
</body>
</html>


