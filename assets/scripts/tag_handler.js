document.addEventListener('DOMContentLoaded', function () {
  let tagsInput = document.querySelector('#tags');
  let tagsContainer = document.createElement('div');
  let hiddenTagsInput = document.querySelector('#post_tags');
  let tags = [];

  tagsContainer.className = 'd-flex flex-wrap gap-2';
  tagsInput.parentNode.insertBefore(tagsContainer, tagsInput.nextSibling);

  tagsInput.addEventListener('keydown', function (e) {
    if (e.key === ',' || e.keyCode === 188) {
      e.preventDefault();
      let tagText = this.value.trim();
      if (tagText.length > 0) {
        createTag(tagText);
      }
      this.value = '';
    }
  });

  function createTag(text) {
    let tag = document.createElement('span');
    tag.className = 'badge bg-secondary';
    tag.textContent = text;
    tagsContainer.appendChild(tag);
    tags.push(text);
    updateHiddenInput();
  }

  function removeTag(text) {
    let index = tags.indexOf(text);
    if (index !== -1) {
      tags.splice(index, 1);
      updateHiddenInput();
    }
  }

  tagsInput.addEventListener('keydown', function (e) {
    if (e.key === 'Backspace' && this.value === '') {
      let lastTag = tagsContainer.lastElementChild;
      if (lastTag) {
        removeTag(lastTag.textContent.trim());
        tagsContainer.removeChild(lastTag);
      }
    }
  });

  function updateHiddenInput() {
    hiddenTagsInput.value = tags.join(','); // Update to set a comma-separated string
  }

  tagsContainer.addEventListener('click', function (e) {
    if (e.target.className.includes('badge')) {
      e.target.remove();
      removeTag(e.target.textContent.trim());
    }
  });
});
