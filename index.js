// DATEPICKER
config = {
  dateFormat: "d.m.Y",
  inline: true,
  minDate: "today",
  maxDate: new Date().fp_incr(90), // 90 days from now
};

flatpickr("#user_date", config);

// TIMEPICKER
$(".starting_time").timepicker({
  timeFormat: "HH:mm",
  interval: 30,
  minTime: new Date(0, 0, 0, 8),
  maxTime: new Date(0, 0, 0, 16),
  dynamic: false,
  dropdown: true,
  scrollbar: true,
});

$(".starting_time").timepicker("option", "change", () => {
  let startHour = parseInt($(".starting_time").val().substring(0, 2));
  let startMinutes = $(".starting_time").val().substring(2, 8);
  let endMinTime = (startHour + 3).toString().concat(startMinutes);

  $(".ending_time").timepicker("option", "minTime", endMinTime);
  $(".ending_time").timepicker("setTime", endMinTime);
});

$(".ending_time").timepicker({
  timeFormat: "HH:mm",
  interval: 30,
  minTime: new Date(0, 0, 0, 8),
  maxTime: new Date(0, 0, 0, 16),
  maxTime: "07:00 PM",
});
