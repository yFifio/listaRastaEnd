document.addEventListener("DOMContentLoaded", () => {
  const tasksContainer = document.getElementById("tasks");

  if (!tasksContainer) {
    return;
  }

  tasksContainer.addEventListener("click", (event) => {
    const editButton = event.target.closest(".edit-btn");
    if (editButton) {
      const task = editButton.closest(".task");
      if (task) {
        const taskView = task.querySelector(".task-view");
        const editForm = task.querySelector(".edit-task");

        taskView.classList.add("hidden");
        editForm.classList.remove("hidden");
      }
    }

    const cancelButton = event.target.closest(".cancel-button");
    if (cancelButton) {
      const task = cancelButton.closest(".task");
      if (task) {
        const taskView = task.querySelector(".task-view");
        const editForm = task.querySelector(".edit-task");

        editForm.classList.add("hidden");
        taskView.classList.remove("hidden");
      }
    }
  });

  const factsSidebar = document.getElementById("rasta-facts-sidebar");
  const factsHandle = document.getElementById("facts-handle");

  if (factsSidebar && factsHandle) {
    factsHandle.addEventListener("click", () => {
      factsSidebar.classList.toggle("open");
    });
  }
});
