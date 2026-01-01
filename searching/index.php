<?php
require '../global.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="search.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&family=Sen:wght@400;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <title>Movie Search</title>
</head>


<body style="background: linear-gradient(to bottom, rgba(0,0,0,0), #151515), url('<?php echo $s3_url; ?>/images/search_background.png'); background-repeat: no-repeat; background-size: cover; background-position: center;">
    <?php include '../navbar/navbar.php'; ?>
    <div class="wrapper">
        <div class="search">
            <input type="text" id="searchInput" placeholder="Search...">
            <div id="suggestions"></div>
            <div class="icon"><ion-icon name="search-outline"></ion-icon></div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
       const searchInput = document.getElementById('searchInput');
        const suggestionsDiv = document.getElementById('suggestions');

        searchInput.addEventListener('input', function() {
            const searchTerm = searchInput.value.trim();

            // Make AJAX request
            if (searchTerm !== '') {
                fetch(`search.php?query=${searchTerm}`)
                    .then(response => response.json())
                    .then(data => {
                        // Log the data to check the response
                        console.log(data);

                        // Update suggestions in the UI
                        const suggestions = data.map(result => ({
                            movieId: result.id,
                            movieName: result.title,
                            ImageUrl: result.cover_img
                        }));
                        suggestionsDiv.classList.add("active");
                        suggestionsDiv.innerHTML = getSuggestionHTML(suggestions, searchTerm);
                    })
                    .catch(error => console.error('Error:', error));
            } else {
                suggestionsDiv.classList.remove("active");
                suggestionsDiv.innerHTML = ''; // Clear suggestions if input is empty
            }
        });

function getSuggestionHTML(suggestions, searchTerm) {
    if (suggestions.length === 0) {
        return '<div class="not_found"> No movies found </div>';
    }

    const suggestionsList = suggestions.map(suggestion => {
        const index = suggestion.movieName.toLowerCase().indexOf(searchTerm.toLowerCase());
        if (index !== -1) {
            const match = suggestion.movieName.substring(index, index + searchTerm.length);
            const after = suggestion.movieName.substring(index + searchTerm.length);
            return `
                <li onclick="selectSuggestion(${suggestion.movieId}, '${suggestion.movieName}')">
                    <div class="suggest_data">
                        <img src="<?php echo $s3_url; ?>/images/${suggestion.ImageUrl}" alt="${suggestion.movieName}" class="suggestion_image">
                        <div class="pr_name">${match}<strong>${after}</strong></div>
                    </div>
                </li>
            `;
        }
        return '';
    }).join(''); // Join the array of suggestions into a single string

    return `<ul>${suggestionsList}</ul>`; // Return the complete list
}

// Select Suggestion to Get Results on the other page
function selectSuggestion(movieId, movieName) {
    // Create a form to post the movie ID
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'set_movie.php';

    // Create an input element for the movie ID
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'movie_id';
    input.value = movieId;

    // Append the input to the form and submit the form
    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
}

// if user hits enter to get the results
searchInput.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        const searchTerm = searchInput.value.trim().toLowerCase();
        const suggestions = suggestionsDiv.querySelectorAll('li');
        let selectedSuggestion = null;
        suggestions.forEach(suggestion => {
            const suggestionText = suggestion.textContent.trim().toLowerCase();
            // Check if the suggestion exactly matches the entered text
            if (suggestionText.includes(searchTerm)) {
                selectedSuggestion = suggestion;
            }
        });
        if (selectedSuggestion) {
            const movieId = selectedSuggestion.getAttribute('onclick').match(/\d+/)[0];
            const movieName = selectedSuggestion.textContent.trim();
            selectSuggestion(movieId, movieName);
        }
    }
});

// if user clicks the search icon
document.querySelector('.icon').addEventListener('click', function() {
    const searchTerm = searchInput.value.trim().toLowerCase();
    const suggestions = suggestionsDiv.querySelectorAll('li');
    let selectedSuggestion = null;
    suggestions.forEach(suggestion => {
        const suggestionText = suggestion.textContent.trim().toLowerCase();
        // Check if the suggestion exactly matches the entered text
        if (suggestionText.includes(searchTerm)) {
            selectedSuggestion = suggestion;
        }
    });
    if (selectedSuggestion) {
        const movieId = selectedSuggestion.getAttribute('onclick').match(/\d+/)[0];
        const movieName = selectedSuggestion.textContent.trim();
        selectSuggestion(movieId, movieName);
    }
});

    </script>
</body>
</html>

