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
                         <small>${entry.date_created}</small>`;
        container.appendChild(div);
      }
    });
}

document.getElementById('guestbook-form').addEventListener('submit', function(e) {
  e.preventDefault();
  const formData = new FormData(this);
  fetch('add_entry.php', {
    method: 'POST',
    body: formData
  }).then(() => {
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
