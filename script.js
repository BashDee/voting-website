// script.js

document.addEventListener("DOMContentLoaded", function () {
    // Add confirmation for voting
    const voteButtons = document.querySelectorAll(".vote-button button");
    voteButtons.forEach(button => {
        button.addEventListener("click", function (e) {
            e.preventDefault();
            const candidateId = this.value;
            if (confirm("Are you sure you want to vote for this candidate?")) {
                castVote(candidateId);
            }
        });
    });
});

function castVote(candidateId) {
    // Example of making a POST request to cast a vote
    fetch("index.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `candidate_id=${candidateId}`
    })
    .then(response => response.text())
    .then(data => {
        alert("Vote submitted!");
        location.reload();  // Reload to update vote count
    })
    .catch(error => console.error("Error:", error));
}
