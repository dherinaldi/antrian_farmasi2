const urlParams = new URLSearchParams(window.location.search);
const poli = urlParams.get("poli"); // "102010201,102010202"
const interval = urlParams.get("interval"); // "102010201,102010202"

/* if (poli) {
  document.getElementById("inp_poli").value = poli;
} */

let poliData = {};
let poliList = [];
let currentIndex = 0;

function updateTime() {
  const now = new Date();
  const dateOptions = {
    weekday: "long",
    year: "numeric",
    month: "long",
    day: "numeric",
  };
  $("#tanggal").text(now.toLocaleDateString("id-ID", dateOptions));
  $("#jam").text(now.toLocaleTimeString("id-ID"));
}

function fetchData() {
  //const poliParam = document.getElementById("inp_poli").value; // ambil isi input text
  $.getJSON("get_antrian_display.php", { poli: poli }, function (data) {
    poliData = data;
    poliList = Object.keys(data);
    showPoli();
  });
}

function showPoli() {
  if (poliList.length === 0) return;

  const poli = poliList[currentIndex];
  const content = poliData[poli];
  $("#poliTitle").text(poli);

  $("#listDipanggil").html(renderList(content.dipanggil));
  $("#listMenunggu").html(renderList(content.menunggu));
  $("#listSudah").html(renderList(content.sudah));

  currentIndex = (currentIndex + 1) % poliList.length;
}

function renderList(list) {
  if (!list || list.length === 0) {
    return '<div class="text-center text-dark">Tidak ada data</div>';
  }
  return list
    .map(
      (item) => `
        <div class="item">
            <div class="no">#${item.no_antrian}</div>
            <div class="nama">${item.nama_pasien}</div>
            <div class="info">${item.no_rm} - ${item.waktu_kunjungan}</div>
        </div>
    `
    )
    .join("");
}

function autoScroll(elementId) {
  const el = document.getElementById(elementId);
  let scrollDown = true;

  setInterval(() => {
    if (scrollDown) {
      el.scrollTop++;
      if (el.scrollTop + el.clientHeight >= el.scrollHeight) scrollDown = false;
    } else {
      el.scrollTop--;
      if (el.scrollTop <= 0) scrollDown = true;
    }
  }, 30);
}

setInterval(updateTime, 1000);
setInterval(showPoli, 10000);
updateTime();
fetchData();
setInterval(fetchData, interval);

["listDipanggil", "listMenunggu", "listSudah"].forEach((id) => autoScroll(id));
