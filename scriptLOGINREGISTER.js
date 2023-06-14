const wrapper = document.querySelector(".wrapper");
const loginLink = document.querySelector(".login-link");
const registerLink = document.querySelector(".register-link");
const btnPopup = document.querySelector(".btnLogin-popup");
const iconClose = document.querySelector(".icon-close");

const search = () => {
  const searchbox = document.getElementById("search-item").value.toUpperCase();
  const storeitem = document.getElementById("konser");
  const product = document.querySelectorAll(".card");
  const pname = storeitem.getElementsByTagName("h5");
  for (var i = 0; i < pname.length; i++) {
    let match = product[i].getElementsByTagName("h5")[0];

    if (match) {
      let textvalue = match.textContent || match.innerHTML;

      if (textvalue.toUpperCase().indexOf(searchbox) > -1) {
        product[i].style.display = "";
      } else {
        product[i].style.display = "none";
      }
      if ((searchbox.value = "")) {
        display = storeitem;
      }
    }
  }
};

registerLink.addEventListener("click", () => {
  wrapper.classList.add("active");
});

loginLink.addEventListener("click", () => {
  wrapper.classList.remove("active");
});

btnPopup.addEventListener("click", () => {
  wrapper.classList.add("active-popup");
});

iconClose.addEventListener("click", () => {
  wrapper.classList.remove("active-popup");
});

// Menangkap klik tombol Logout
document.getElementById("logoutBtn").addEventListener("click", function () {
  // Mengubah tombol menjadi tombol Login
  this.innerHTML =
    '<button type="button" class="btnLogin-popup btn-primary" data-toggle="modal" data-target="#exampleModalLong">🔒 | Login </button>';
});
