<?php

error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/file.log');

$username = "PDADCBFMUF";
$password = "CTSAdm";

$conn = oci_connect($username, $password, "(DESCRIPTION =
(ADDRESS = (PROTOCOL = TCP)(HOST = 161.97.85.250)(PORT = 1521))
(CONNECT_DATA =
  (SID = ORCL)
  (PRESENTATION = RO)
)
)");

if (!$conn) {
    $error = oci_error();
    die('Connection failed: ' . $error['message']);
}

// $sql = "SELECT alert_id, name FROM PDCMCBFMUFCB_CMS_ALERTS";
$sql = "SELECT alert_id, alert FROM pdcmcbfmuf.cb_cms_alerts";

$stmt = oci_parse($conn, $sql);
oci_execute($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<link href="css/all.min.css" rel="stylesheet">
<link href="css/fontawesome.min.css" rel="stylesheet">
<link href="style.css" rel="stylesheet">
<link href="fonts.css" rel="stylesheet">

<title>Sidebar Example</title>
<style>
    body{
        color:black;
        overflow-y:hidden;
        font-family: 'Poppins', sans-serif;
        font-size: 0.875rem; /* 14px */
        line-height: 1.25rem; /* 20px */
    }
    .sidebar {
      width: 30%;
      height: 100vh;
    }
    .upper-section {
      height: 70%;
      /* background-color: #f0f0f0; */
      background-color: #E5E7EB;

    }
    .lower-section {
      height: 30%;
      /* background-color: #d0d0d0; */
      background-color: #D1D5DB;
    }

  .flex-1 {
    display: flex;
    flex-direction: column;
    /* justify-content: space-between; */
  }
  #custom-loader {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(255, 255, 255, 0.8);
    }

    .loader-spinner {
      border: 4px solid #f3f3f3;
      border-top: 4px solid #333;
      border-radius: 50%;
      width: 50px;
      height: 50px;
      animation: spin 2s linear infinite;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
    }

    @keyframes spin {
      0% { transform: translate(-50%, -50%) rotate(0deg); }
      100% { transform: translate(-50%, -50%) rotate(360deg); }
    }
</style>
</head>
<body class="bg-gray-100">

<div class="flex">
<div id="custom-loader">
  <div class="loader-spinner"></div>
</div>
  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Upper Section -->
    <div class="upper-section p-3 overflow-y-auto">
    <h2 class="text-lg font-bold mb-4">Generic</h2>
    <!-- title and buttons -->
      <div class="flex gap-2">
        <button class="button" id="addButton">
          <i class="fas fa-plus mr-2 text-green-500 text-md"></i>Add
        </button>
        <button class="button" id="deleteButton">
          <i class="fas fa-times mr-2 text-red-500 text-md"></i>Delete
        </button>
      </div>
      <!-- All Alarms From DB -->
    <div class="row mt-3" id="row1">
    <?php while ($row = oci_fetch_assoc($stmt)): ?>
        <div class="mt-1">
      <input type="checkbox" class="checkbox mr-1" data-alertid="<?php echo $row['ALERT_ID']; ?>" onclick="handleCheckboxClick(this)" />
        <label for="checkbox_<?php echo $row['ALERT_ID']; ?>" onclick="handleLabelClick(this)" class="cursor-pointer p-1 rounded"><?php echo $row['ALERT'] ?></label>
        </div>
    <?php endwhile;
     oci_free_statement($stmt);
     oci_close($conn);
    ?>
    </div>
    </div>
      
    <input type="hidden" id="clickedLabelId" value="">
    <!-- Lower Section -->
    <div class="lower-section p-2">
    <?php include('AlertLabels.html'); ?>
    </div>
  </div>

  <!-- Content -->
  <div class="content flex-1 bg-white">
  <?php include('Generic.html'); ?>
  </div>
</div>

<script>
    var focusedLabel = null; // Initialize a variable to store the currently focused label

    let alertIDFromCheckbox;

    let addButton = document.getElementById("addButton");

    function showLoader() {
    document.getElementById("custom-loader").style.display = "block";
  }

  function hideLoader() {
    document.getElementById("custom-loader").style.display = "none";
  }

  addButton.addEventListener("click", () => {
    selectedData = [];
    document.getElementById("name").value = "";
    document.getElementById("type").value = "";
    document.getElementById("severity").value = "";
    document.getElementById("container").innerHTML = "";
    showLoader();
    fetch("getIdFromSequence.php", {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
      },
    })
      .then((response) => response.json())
      .then((data) => {
        console.log("Response", data);
        console.log("Max Alert Id", data.alert_id);
        document.getElementById("alert_id").value = data.alert_id;
        document.getElementById("identifier").value = "Add";
        document.getElementById("globalGroupOperator").value = "";
        console.log("Identifier : ",document.getElementById("identifier").value);
        const checkboxes = document.querySelectorAll(".checkbox"); // Select all checkboxes with class "checkbox"

        const valueInputs = document.querySelectorAll(".value-input"); // Select all input fields with class "value-input"

        for (const checkbox of checkboxes) {
          if (checkbox.checked) {
            checkbox.checked = false; // Uncheck the checkbox
          }
        }

        for (const input of valueInputs) {
          if (input.value !== "") {
            input.value = ""; // Set the value to null
          }
        }
        hideLoader();
      })
      .catch((error) => {
        console.error("Error", error.stack);
        hideLoader();
      });
  });

//   function parseClause(clause) {

//   const conditions = clause.split("AND");

//   const trimmedArray = conditions.map((element) => element.trim());
  
//   const parsedData = trimmedArray.map((input) => {
//     var pattern = /(\w+)\s*\(\s*(.*?)\s*(>=)\s*'(\d+)'\s*\)/;
//     var match = pattern.exec(input);
//     if (match) {
//       const keyword = match[1];
//       const name = match[2];
//       const operator = match[3];
//       const value = match[4];
//       console.log(keyword, name, operator, value);
//       return { name, operator, value };
//     }
//     return null;
//   });
//   return parsedData;
// }

const populateRule = (ruleData, parentGroup) => {
  createRule(parentGroup, ruleData);
};

// Function to create and populate a group
const populateGroup = (groupData, parentGroup) => {
  const newGroup = createGroup(parentGroup,groupData);
  groupData.rules.forEach((ruleOrGroup) => {
    if (ruleOrGroup.type === "Single") {
      populateRule(ruleOrGroup, newGroup);
    } else if (ruleOrGroup.type === "Group") {
      populateGroup(ruleOrGroup, newGroup);
    }
  });
};

function parseValue(value) {
  return value;
}

function parseWhereClause(whereClause) {
  const dataArray = [];

  // Split the where clause into segments based on group operators
  const segments = whereClause.split(/\s+(OR|AND)\s+(?![^(]*\))/);

  let currentGroupOperator = "";

  segments.forEach((segment) => {
    if(segment === "AND" || segment === "OR"){
      document.getElementById("globalGroupOperator").value = segment;
    }
    else if(segment.startsWith("(") && segment.endsWith(")")) {
      // Group segment
      console.log("Inside Group")
      const groupOperatorMatch = segment.match(/\s+(AND|OR)\s+/);
      if (groupOperatorMatch) {
        currentGroupOperator = groupOperatorMatch[1];
      }
      const groupWhereClause = segment.slice(1, -1);
      const groupData = parseWhereClause(groupWhereClause);
      dataArray.push({ type: "Group", groupOperator: currentGroupOperator, rules: groupData });
    } else {
      // Single rule segment
      const regex = /(\w+)\s*([^\s]+)\s*'([^']+)'/;
      const match = segment.match(regex);

      if(match){
        const field = match[1];      // The field (EVENTTYPE)
        const operator = match[2];   // The operator (=)
        const value = match[3];      // The value ('MOC')

        dataArray.push({ type: "Single", field, operator,value: parseValue(value) });
      }else{
        console.log("No match found.");
      }
    }
  });

  return dataArray;
}

function parseClause(clause) {
  const dataArray = [];

  // Remove leading and trailing parentheses if present
  clause = clause.replace(/^\(/, '').replace(/\)$/, '');

  // Split the where clause into segments based on the "AND" operator
  const segments = clause.split(/\s+AND\s+/);

  segments.forEach((segment) => {
    // Split each segment into field, operator, and value using regex
    const match = segment.match(/\s*\((.*?)\)\s*(>=|<=|>|<|=)\s*(['"]?)([^'"]+)\3/);

    if (match) {
      const name = match[1];
      const operator = match[2];
      const value = match[4];

      dataArray.push({name, operator, value });
    }
  });

  return dataArray;
}




function handleLabelClick(label) {
    // getting clicked label id to get data form db
    const checkboxId = label.getAttribute('for');
    var alertId = checkboxId.replace("checkbox_", "");
    document.getElementById("clickedLabelId").value = alertId;
    document.getElementById("alert_id").value = alertId;
    document.getElementById("identifier").value = "Update";
    document.getElementById("globalGroupOperator").value = "";
    console.log(alertId);

    selectedData = [];

    document.getElementById("container").innerHTML = "";

    const checkboxes = document.querySelectorAll(".checkbox"); // Select all checkboxes with class "checkbox"

        const valueInputs = document.querySelectorAll(".value-input"); // Select all input fields with class "value-input"

        for (const checkbox of checkboxes) {
          if (checkbox.checked) {
            checkbox.checked = false; // Uncheck the checkbox
          }
        }

        for (const input of valueInputs) {
          if (input.value !== "") {
            input.value = ""; // Set the value to null
          }
        }

    showLoader();

    fetch('GetAlarm.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: 'alert_id=' + encodeURIComponent(alertId),
  })
  .then(response => response.json())
  .then(data => {
    hideLoader();
    console.log("Response",data);
    document.getElementById("name").value = data.data[0].ALERT;
    // document.getElementById("type").value = data.data[0].TYPE;
    document.getElementById("severity").value = data.data[0].SEVERITY;
    currentClause = data.data[0].Q_CRITERIA;

    if(data.data[0].Q_FILTER !== null){
      console.log("Where Clause",data.data[0].Q_FILTER)
      const selectedD = parseWhereClause(data.data[0].Q_FILTER);
      console.log("From Where Clause",selectedD);
      selectedD.forEach((ruleOrGroup) => {
    if (ruleOrGroup.type === "Single") {
      populateRule(ruleOrGroup, null);
    } else if (ruleOrGroup.type === "Group") {
      populateGroup(ruleOrGroup, null);
    }
    });
    }

    // Parse the clause to get conditions and values
    if(data.data[0].Q_CRITERIA !== null){
      console.log(data.data[0].Q_CRITERIA);
      const parsedData = parseClause(data.data[0].Q_CRITERIA);
      console.log(parsedData);

    for (const data of parsedData) {
      const row = rowData.find((rowData) => rowData.label === data?.name);
      if (row) {
        const checkbox = document.querySelector(`.checkbox[data-row="${row.row}"]`);
        if (checkbox) {
          checkbox.checked = true;
          // Populate the associated value input field
          const valueInput = document.querySelector(`.value-input[data-row="${row.row}"]`);
          if (valueInput) {
            valueInput.value = parseInt(data.value);
          }
        }
      }
    }
  }


  });

    // focusing on click
    if (focusedLabel !== null) {
        // Reset styles of the previously focused label
        focusedLabel.style.backgroundColor = "";
        focusedLabel.style.color = "";
    }

    if (focusedLabel !== label) {
        label.style.backgroundColor = "#D1D5DB"; // Change to desired color
        focusedLabel = label; // Update the focused label reference
    } else {
        focusedLabel = null; // Clear the focused label reference
    }
}

function handleCheckboxClick(checkbox) {
        var alertId = checkbox.getAttribute("data-alertid");

        console.log("Checkbox Alert ID: " + alertId);

        alertIDFromCheckbox = alertId;
    }

    const deleteButton = document.getElementById("deleteButton");

    deleteButton.addEventListener("click", () => {
    showLoader();
    fetch('DeleteAlarm.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: 'alert_id=' + encodeURIComponent(alertIDFromCheckbox),
  })
  .then(response => response.json())
  .then(data => {
    location.reload();
    console.log("Response",data);
    hideLoader();
  })
  .catch(error => {
    console.error("Error",error.stack);
  });
  });

</script>
</body>
</html>
