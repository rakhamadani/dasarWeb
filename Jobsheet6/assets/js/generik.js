// Fungsi generik untuk memuat data JSON dan merender tabel secara dinamis
async function muatTabelDinamis(urlFile, daftarKunci) {
  const tbody = document.querySelector(".table-responsive table tbody");
  const loading = document.getElementById("loading-indicator");
  if (!tbody) return;

  loading.style.display = "block";
  tbody.innerHTML = "";

  try {
    // Simulasi delay jaringan
    await new Promise((resolve) => setTimeout(resolve, 600));
    
    const res = await fetch(urlFile);
    if (!res.ok) {
      throw new Error("Gagal mengambil data (status " + res.status + ")");
    }
    
    const dataList = await res.json();

    dataList.forEach(function (item) {
      const tr = document.createElement("tr");
      let htmlContent = "";

      // Loop berdasarkan daftar kunci properti yang diberikan
      daftarKunci.forEach(function (kunci) {
        htmlContent += "<td>" + item[kunci] + "</td>";
      });

      // Tambahkan kolom tombol Aksi (Edit & Hapus) di akhir baris
      htmlContent += 
        "<td>" +
        "<button type=\"button\">Edit</button> " +
        "<button type=\"button\" class=\"btn-hapus\">Hapus</button>" +
        "</td>";

      tr.innerHTML = htmlContent;
      tbody.appendChild(tr);
    });
  } catch (err) {
    tbody.innerHTML =
      "<tr><td colspan=\"" + (daftarKunci.length + 1) + "\">Gagal memuat data: " + err.message + "</td></tr>";
  } finally {
    loading.style.display = "none";
  }
}

document.addEventListener("DOMContentLoaded", function () {
  muatTabelDinamis("../data/buku.json", ["judul", "pengarang", "tahun", "stok"]);
});