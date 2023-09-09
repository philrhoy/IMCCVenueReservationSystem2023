var cal = {
  // (A) SUPPORT FUNCTION - AJAX CALL
  ajax: function (data, load) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "3d-ajax.php");
    if (load) { xhr.onload = load; }
    xhr.send(data);
  },

  // (B) ON PAGE LOAD - ATTACH LISTENERS + DRAW
  init: function () {
    document.getElementById("calmonth").addEventListener("change", cal.draw);
    document.getElementById("calyear").addEventListener("change", cal.draw);
    document.getElementById("calvenue").addEventListener("change", cal.draw);
    //  $("#calvenue").on("change", cal.draw);
    // document.getElementById("calformdel").addEventListener("click", cal.del);
    // document.getElementById("calform").addEventListener("submit", cal.save);
    document.getElementById("calformcx").addEventListener("click", cal.hide);
    cal.draw();
  },

  // (C) DRAW CALENDAR
  draw: function () {
    // (C1) FORM DATA
    let data = new FormData();
    data.append("req", "draw");
    data.append("month", document.getElementById("calmonth").value);
    data.append("year", document.getElementById("calyear").value);
    data.append("venue", $('#calvenue option:selected').val());

    // (C2) ATTACH CLICK TO UPDATE EVENT ON AJAX LOAD
    cal.ajax(data, function () {
      let wrapper = document.getElementById("calwrap");
      wrapper.innerHTML = this.response;
      let all = wrapper.getElementsByClassName('day');
      for (let day of all) {
      //  day.addEventListener("click", cal.show);
      }
      all = wrapper.getElementsByClassName('calevt');
      if (all.length != 0) {
        for (let evt of all) {
          evt.addEventListener("click", cal.show);
        }
      }
    });
  },

  // (D) SHOW EVENT DOCKET
  show: function (evt) {
    let eid = this.getAttribute("data-eid");

    // (D1) ADD NEW EVENT
    if (eid === null) {

      let year = document.getElementById("calyear").value,
        month = document.getElementById("calmonth").value,
        day = this.dataset.day;
      if (month.length == 1) { month = "0" + month; }
      if (day.length == 1) { day = "0" + day; }
      document.getElementById("calform").reset();
      document.getElementById("evtid").value = "";
      // document.getElementById("RID").value = sequence;
      document.getElementById("evtstart").value = `${year}-${month}-${day}`;
      document.getElementById("evtend").value = `${year}-${month}-${day}`;
      $("#evtvenue").val([]).trigger("change");
      document.getElementById("calformdel").style.display = "none";
    }

    // (D2) EDIT EVENT
    else {
      let edata = JSON.parse(document.getElementById("evt" + eid).innerHTML);
      document.getElementById("evtid").value = eid;
      document.getElementById("evtstart").value = edata['date_start'];
      document.getElementById("evtend").value = edata['date_end'];
      $("#evtvenue").val(edata['venueID']).trigger("change");
      document.getElementById("evtname").value = edata['name'];
      // document.getElementById("evtvenue").value = edata['venueID'];
      // document.getElementById("evtcontact").value = edata['contact'];
      document.getElementById("evttxt").value = edata['description'];
      // document.getElementById("evtcolor").value = edata['tag_color'];
      // document.getElementById("calformdel").style.display = "block";
    }

    // (D3) SHOW DOCKET
    document.getElementById("calblock").classList.add("show");
    evt.stopPropagation();
  },

  // (E) HIDE EVENT DOCKET
  hide: function () {
    document.getElementById("calblock").classList.remove("show");
  },

  // (F) SAVE EVENT
  save: function (evt) {
    // (F1) FORM DATA
    let data = new FormData(),
      eid = document.getElementById("evtid").value;
    data.append("req", "save");
    data.append("RID", document.getElementById("RID").value);
    data.append("reservationID", document.getElementById("reservationID").value);
    data.append("name", document.getElementById("evtname").value);
    //data.append("address", document.getElementById("evtaddress").value);
    // data.append("contact", document.getElementById("evtcontact").value);
    data.append("start", document.getElementById("evtstart").value);
    data.append("end", document.getElementById("evtend").value);
    data.append("venueID", $('#evtvenue option:selected').val());

    data.append("txt", document.getElementById("evttxt").value);
    // data.append("color", document.getElementById("evtcolor").value);
    if (eid != "") { data.append("eid", eid); }

    // (F2) AJAX SAVE
    cal.ajax(data, function () {
      if (this.response == "OK") { alert('Creating event reservation succesfully saved.'); cal.hide(); cal.draw(); window.location.reload(); }
      else { alert(this.response); }
    });
    evt.preventDefault();
  },

  // (G) DELETE EVENT
  del: function () {
    if (confirm("Delete Schedule?")) {
      // (G1) FORM DATA
      let data = new FormData();
      data.append("req", "del");
      data.append("eid", document.getElementById("evtid").value);

      // (G2) AJAX DELETE
      cal.ajax(data, function () {
        if (this.response == "OK") { alert('Event reservation succesfully deleted.'); cal.hide(); cal.draw(); }
        else { alert(this.response); }
      });
    }
  }
};
window.addEventListener("DOMContentLoaded", cal.init);