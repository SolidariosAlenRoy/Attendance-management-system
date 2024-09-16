function validateForm() {
  const username = document.getElementById('username').value;
  const password = document.getElementById('password').value;
  const errorMessage = document.getElementById('error-message');

  if (username === "" || password === "") {
    errorMessage.textContent = "Both fields are required!";
    return false;
  }

  if (username === "student" && password === "123456") {
    alert("Login successful!");
    return true;
  } else {
    errorMessage.textContent = "Invalid username or password!";
    return false;
  }
}
