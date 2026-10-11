function initNavToggle() {
  const toggleBtn = document.getElementById("nav-toggle-btn");
  const nav = document.querySelector("header nav");
  if (!toggleBtn || !nav) return;

  toggleBtn.addEventListener("click", function () {
    nav.classList.toggle("nav-open");
  });
}

// Memakai event delegation di document karena baris tabel sekarang
// dirender dinamis via fetch (lihat buku.js/anggota.js) sehingga
// tombol .btn-hapus belum tentu ada saat DOMContentLoaded.
function initHapusConfirm() {
  document.addEventListener("click", function (e) {
    // Tambahkan console.log untuk melihat elemen apa saja yang diklik di halaman
    console.log(e.target);

    const btn = e.target.closest(".btn-hapus");
    if (!btn) return;
    const row = btn.closest("tr");
    const nama = row ? row.querySelector("td")?.textContent : "data ini";
    const yakin = confirm("Yakin ingin menghapus \"" + nama + "\"?");
    if (yakin && row) {
      row.remove();
    }
  });
}

function initTableFilter() {
  const input = document.getElementById("search-input");
  const table = document.querySelector(".table-responsive table");
  if (!input || !table) return;

  updateCounter();

  input.addEventListener("keyup", function () {
    const keyword = input.value.toLowerCase();
    const rows = table.querySelectorAll("tbody tr");

    rows.forEach(function (row) {
      const kolomJudul = row.querySelector("td");
      
      if (kolomJudul) {
        const teksJudul = kolomJudul.textContent.toLowerCase();
        row.style.display = teksJudul.includes(keyword) ? "" : "none";
      }
    });
  });
}

function tampilkanError(input, pesan) {
  hapusError(input);
  const span = document.createElement("span");
  span.className = "error";
  span.textContent = pesan;
  input.insertAdjacentElement("afterend", span);
}

function hapusError(input) {
  const next = input.nextElementSibling;
  if (next && next.classList.contains("error")) {
    next.remove();
  }
}

function initValidasiForm() {
  const form = document.getElementById("form-tambah");
  if (!form) return;

  form.addEventListener("submit", function (e) {
    let valid = true;

    const judul = form.querySelector('[name="judul"], [name="nama"]');
    if (judul && judul.value.trim() === "") {
      tampilkanError(judul, "Field ini wajib diisi.");
      valid = false;
    } else if (judul) {
      hapusError(judul);
    }

    const isbnInput = form.querySelector('[name="isbn"]');
    if (isbnInput && isbnInput.value.trim() !== "") {
      const isbnPattern = /^[0-9-]+$/;
      if (!isbnPattern.test(isbnInput.value.trim())) {
        tampilkanError(isbnInput, "ISBN hanya boleh berisi angka dan tanda hubung (-).");
        valid = false;
      } else {
        hapusError(isbnInput);
      }
    }

    // 1. Daftar nama field yang wajib diisi (Required Fields)
    const requiredFields = ["judul", "nama", "pengarang", "penerbit", "kategori"];

    // 2. Loop array requiredFields dengan forEach
    requiredFields.forEach(function (fieldName) {
      const input = form.querySelector('[name="' + fieldName + '"]');
      
      if (input) {
        if (input.value.trim() === "") {
          tampilkanError(input, "Field ini wajib diisi.");
          valid = false;
        } else {
          hapusError(input);
        }
      }
    });

    // 3. Validasi Tambahan Khusus (Misal: Angka / Rentang Nilai)
    
    // Validasi Tahun (jika elemennya ada di form)
    const tahunInput = form.querySelector('[name="tahun"]');
    if (tahunInput && tahunInput.value.trim() !== "") {
      const tahunVal = parseInt(tahunInput.value, 10);
      if (isNaN(tahunVal) || tahunVal < 1900 || tahunVal > 2099) {
        tampilkanError(tahunInput, "Masukkan tahun yang valid (1900 - 2099).");
        valid = false;
      }
    }

    // Validasi Stok (jika elemennya ada di form)
    const stokInput = form.querySelector('[name="stok"]');
    if (stokInput && stokInput.value.trim() !== "") {
      const stokVal = parseInt(stokInput.value, 10);
      if (isNaN(stokVal) || stokVal < 0) {
        tampilkanError(stokInput, "Stok tidak boleh bernilai negatif.");
        valid = false;
      }
    }

    if (!valid) {
      e.preventDefault();
    }
  });
}



function updateCounter() {
  const counterEl = document.getElementById("table-counter");
  const table = document.querySelector(".table-responsive table");
  if (!counterEl || !table) return;

  const rows = table.querySelectorAll("tbody tr");
  const totalRows = rows.length;
  let visibleRows = 0;

  rows.forEach(function (row) {
    if (row.style.display !== "none") {
      visibleRows++;
    }
  });

  counterEl.textContent = "Menampilkan " + visibleRows + " dari " + totalRows + " data";
}

document.addEventListener("DOMContentLoaded", function () {
  initNavToggle();
  initHapusConfirm();
  initTableFilter();
  initValidasiForm();
  updateCounter();
});