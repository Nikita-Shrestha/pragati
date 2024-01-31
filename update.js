document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("modal");
    const modalTeacherUpdate = document.getElementById("modal-teacher-update");
  
    // Function to open the modal and set the form data
    function openModal(teacherId) {
      modal.style.display = "block";
      modalTeacherUpdate.value = teacherId;
    }
  
    // Event listener for the "Update" buttons in the table
    const updateButtons = document.querySelectorAll(".update.btn");
    updateButtons.forEach((button) => {
      button.addEventListener("click", function (event) {
        event.preventDefault(); // Prevent form submission
  
        const teacherId = button.parentElement.querySelector("input[name='teacher_update']").value;
        openModal(teacherId);
      });
    });
  
    // Close the modal when the user clicks outside of it
    window.addEventListener("click", function (event) {
      if (event.target === modal) {
        modal.style.display = "none";
      }
    });
  });
  