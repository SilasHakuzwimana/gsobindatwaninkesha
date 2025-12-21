$(document).ready(function () {
    loadActivities();
});

function loadActivities() {
    $.ajax({
        url: "../api/get_user_activities.php",
        method: "GET",
        dataType: "json",

        success: function (data) {
            let tbody = $("#activityBody");
            tbody.empty();

            if (data.length === 0) {
                tbody.append(`<tr><td colspan="6" class="text-center">No records found</td></tr>`);
                return;
            }

            data.forEach((item, i) => {
                tbody.append(`
                    <tr>
                        <td>${i + 1}</td>
                        <td>${item.activity_type}</td>
                        <td>${item.activity_description || "-"}</td>
                        <td>${item.ip_address || "-"}</td>
                        <td>${item.user_agent || "-"}</td>
                        <td>${item.created_at}</td>
                    </tr>
                `);
            });
        },

        error: function () {
            $("#activityBody").html(
                `<tr><td colspan="6" class="text-danger text-center">Error loading data</td></tr>`
            );
        }
    });
}
