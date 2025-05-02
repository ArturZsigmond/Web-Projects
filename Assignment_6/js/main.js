let page = 1;

function loadEntries() {
  fetch(`fetch_entries.php?page=${page}`)
    .then(response => response.json())
    .then(entries => {
      const container = document.getElementById('entries-container');
      container.innerHTML = '';
      for (const entry of entries) {
        const div = document.createElement('div');
        div.className = 'entry';
        div.innerHTML = `<h3>${entry.title}</h3>
                 <p><strong>${entry.author_email}</strong></p>
                 <p>${entry.comment}</p>
                 <p>Rating: ${entry.rating} stars</p>
                 <small>${entry.date_created}</small>`;
        container.appendChild(div);
      }
    });
}

document.getElementById('guestbook-form').addEventListener('submit', function(e) {
  e.preventDefault();

  const title = this.title.value.trim();
  const comment = this.comment.value.trim();

  if (title.length < 3) {
    alert("Title must be at least 3 characters long.");
    return;
  }

  if (comment.length < 5) {
    alert("Comment must be at least 5 characters long.");
    return;
  }

  const formData = new FormData(this);

  fetch('add_entry.php', {
    method: 'POST',
    body: formData
  }).then(res => {
    if (!res.ok) {
      return res.text().then(msg => alert("Server error: " + msg));
    }
    this.reset();
    loadEntries();
  });
});

document.getElementById('prev-page').onclick = function() {
  if (page > 1) page--;
  loadEntries();
};

document.getElementById('next-page').onclick = function() {
  page++;
  loadEntries();
};

window.onload = loadEntries;
