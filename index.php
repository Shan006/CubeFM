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

$sql = "SELECT alert_id, alert FROM PDCMCBFMUFCB_CMS_ALERTS";

$stmt = oci_parse($conn, $sql);
oci_execute($stmt);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Alarms</title>
  <link href="css/all.min.css" rel="stylesheet">
  <link href="css/fontawesome.min.css" rel="stylesheet">
  <link href="style.css" rel="stylesheet">
  <link href="fonts.css" rel="stylesheet">
  <style>
    body {
        font-family: 'Poppins', sans-serif;
        font-size: 0.875rem; /* 14px */
        line-height: 1.25rem; /* 20px */
    }
    .hidden{
        display: none;
    }
    .visible{
        display: block;
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

    .custom-red-icon {
      color: red;
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
    #right-random{
      font-size: 1rem; /* 16px */
      line-height: 1.5rem; /* 24px */
      margin:17rem;
    }
    .group-arrow {
      border-top: 10px solid transparent;
      border-bottom: 10px solid transparent;
      border-left: 10px solid red;
      margin-right: 10px;
    }

    .filter-column{
      color:blue;
      /* border:1px solid blue; */
    }

    .filter-operator{
      color:green;
      /* border:1px solid green; */
    }
    .filter-value{
      color:blue;
      /* border:1px solid blue; */
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

  </style>
</head>
<body>
<div class="container h-screen flex">
  <div class="container min-h-screen flex flex-col md:flex-row font-poppins">
    <div id="custom-loader">
    <div class="loader-spinner"></div>
    </div>
    <div class="left-section md:w-1/5 text-black flex flex-col items-start p-4">
      <h2 class="text-2xl font-bold mb-4">Alarms</h2>
      <div class="flex gap-2">
        <button class="button" id="addButton">
          <i class="fas fa-plus mr-2"></i>Add
        </button>
        <button class="button" id="deleteButton">
          <i class="fas fa-times mr-2"></i>Delete
        </button>
      </div>
      <div class="flex flex-col gap-2 my-3">
  <div class="row" id="row1">
    <?php while ($row = oci_fetch_assoc($stmt)): ?>
      <div>
      <input type="checkbox" class="checkbox mr-1" data-alertid="<?php echo $row['ALERT_ID']; ?>" onclick="handleCheckboxClick(this)" />
        <label for="checkbox_<?php echo $row['ALERT_ID']; ?>" onclick="handleLabelClick(this)" class="cursor-pointer"><?php echo $row['ALERT'] ?></label>
      </div>
    <?php endwhile;
     oci_free_statement($stmt);
     oci_close($conn);
    ?>
  </div>
</div>
    </div>
    <div id="right-random">
      <h1>Click Any Alarm To View Extra Information</h1>
    </div>
    <div class="right-section mx-3 md:w-4/5 flex-col hidden" id="right-section">
      <form method="post" action="UpdateAlarm.php">
        <div class="section top-section bg-white text-black p-4">
          <div class="flex items-center justify-between">          
          <h5>General</h5>
          <button type="submit" id="submitButton" name="submitButton" class="bg-gray-900 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-full transition duration-300 ease-in-out mb-2" value="Save">
            Save
          </button>          
        </div>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-2">
            <label for="name" class="label">Name:</label>
            <input type="text" id="name" name="alert" class="input border-solid border-2 border-gray-900" />

            <label for="type" class="label">ID:</label>
            <input type="text" id="id" name="alert_id" class="input border-solid border-2 border-gray-900" />

            <label for="severity" class="label">Severity:</label>
            <select id="severity" name="severity" class="input border-solid border-2 border-gray-900">
              <option value="Select">Select</option>
              <option value="Low">Low</option>
              <option value="Medium">Medium</option>
              <option value="High">High</option>
              <option value="Critical">Critical</option>
            </select>

            <label for="interval" class="label">Time Interval:</label>
            <select id="interval" name="interval" class="input border-solid border-2 border-gray-900">
              <option value="Select">Select</option>
              <option value="30M">30 Minutes</option>
              <option value="1H">1 Hour</option>
              <option value="4H">4 Hours</option>
              <option value="6H">6 Hours</option>
              <option value="12H">12 Hours</option>
              <option value="24H">24 Hours</option>
            </select>

            <label for="status" class="label">Status:</label>
            <select id="status" name="status" class="input border-solid border-2 border-gray-900">
              <option value="Select">Select</option>
              <option value="Pending">Pending</option>
              <option value="Completed">Completed</option>
            </select>
          </div>
        </div>
        <!-- <div class="section middle-section p-4 text-black">
          <h5 class="mb-2">Alarm Criteria</h5>
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
            <div class="flex items-center">
              <label for="eventCount">Event Count</label>
            </div>
            <input type="number" class="input border-solid border-2 border-gray-900" id="eventCountInput" name="eventcount" />
            <br/>

            <div class="flex items-center">
              <label for="dataVolume">Data Volume</label>
            </div>
            <input type="number" class="input border-solid border-2  border-gray-900" step="1" id="dataVolumeInput" name="datavolume" />
            <br/>

            <div class="flex items-center">
              <label for="eventDuration">Call Duration</label>
            </div>
            <input type="number" class="input border-solid border-2  border-gray-900" step="1" id="eventDurationInput" name="callduration" />
            <br/>

            <div class="flex items-center">
              <label for="amount">Amount</label>
            </div>
            <input type="number" class="input border-solid border-2  border-gray-900" step="1" id="amountInput" name="amount" />
          </div>
        </div> -->
        <div class="p-4">
        <h5 class="mb-2">Alarm Criteria</h5>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-2">
            <label for="eventCount">Event Count:</label>
            <input type="number" class="input border-solid border-2 border-gray-900" id="eventCountInput" name="eventcount" />
  
            <label for="dataVolume">Data Volume:</label>
            <input type="number" class="input border-solid border-2  border-gray-900" step="1" id="dataVolumeInput" name="datavolume" />

            <label for="eventDuration">Call Duration:</label>
            <input type="number" class="input border-solid border-2  border-gray-900" step="1" id="eventDurationInput" name="callduration" />

            <label for="amount">Amount:</label>
            <input type="number" class="input border-solid border-2  border-gray-900" step="1" id="amountInput" name="amount" />
          </div>

        <div class="section bottom-section my-4 bg-white text-black">
          <h5>Alarm Filter Rules</h5>
          <div class="button-row flex">
            <button class="button flex items-center" id="addGroupButton" type="button">
              <i class="fas fa-plus mr-1"></i>Group
            </button>
            <button class="button flex items-center ml-3" id="addAndButton" type="button">
              <i class="fas fa-plus mr-1"></i>AND
            </button>
            <button class="button flex items-center ml-3" id="addOrButton" type="button">
              <i class="fas fa-plus mr-1"></i>OR
            </button>
          </div>
          <div id="whereClauseContainer" class="section where-section bg-white text-black mt-3">
            <div id="whereClause"></div>
            <input type="hidden" name="q_filter" id="q_filter"/>
            <button type="button" id="showWhereButton" class="button">Show SQL WHERE Clause</button>
          </div>
          <div class="overflow-y-scroll mt-3" id="filterRulesContainer">
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

  <script>
    let alertIDForCheckbox;
    let alertIDForLabel;
    const rightSide = document.getElementById("right-section");
    const filterRulesContainer = document.getElementById("filterRulesContainer");

    let filterRules = [];

    let selectedRow = null;

  const handleRowClick = (index) => {
    selectedRow = index;
    render();
  };

      const handleAddRule = (type) => {
        const newRule = {
          type: type,
          column: "",
          operator: "",
          value: "",
        };
        filterRules.push(newRule);
        render();
      };

      const handleDeleteRule = (index) => {
        filterRules.splice(index, 1);
        render();
      };

      const handleRuleChange = (index, field, value) => {
        filterRules[index][field] = value;
        render();
      };

  const handleRuleChangeForGroup = (groupIndex, ruleIndex, field, value) => {
    filterRules[groupIndex].rules[ruleIndex] = {
      ...filterRules[groupIndex].rules[ruleIndex],
      [field]: value,
    };
    render();
  };

  const handleOperatorForGroup = (groupIndex, value) => {
    filterRules[groupIndex].groupOperator = value;
    render();
  };

  const handleAddGroup = () => {
    const newGroupRule = {
      type: "GROUP",
      groupOperator:"",
      rules: [],
    };
    filterRules.push(newGroupRule);
    render();
  };

  const handleAddRuleToGroup = (groupIndex, type) => {
    const newRule = {
      type: type,
      column: "",
      operator: "",
      value: "",
    };
    filterRules[groupIndex].rules.push(newRule);
    render();
  };

  const handleDeleteRuleFromGroup = (groupIndex, ruleIndex) => {
    filterRules[groupIndex].rules.splice(ruleIndex, 1);
    render();
  };

  const handleDeleteGroup = (groupIndex) => {
    filterRules.splice(groupIndex, 1);
    render();
  };

    const render = () => {

    filterRulesContainer.innerHTML = "";

    filterRulesContainer.style.height = '230px';

    filterRules.forEach((rule, index) => {
      console.log("Rule",rule);
      if (rule.type === "GROUP") {
        const groupRule = createDOMElement("div", {
          class:
            "group-rule flex items-center p-3",
        });
        filterRulesContainer.appendChild(groupRule);

        // Add the arrow element
        const arrow = createDOMElement("div", {
          class: "group-arrow",
        });
        groupRule.appendChild(arrow);

        const groupRuleContent = createDOMElement("div", {
          class: "group-rule-content",
        });
        groupRule.appendChild(groupRuleContent);


        const removeGroupButton = createDOMElement("button", {
          class: "group-rule-type flex items-center",
        });
        removeGroupButton.addEventListener("click", () =>
          handleDeleteGroup(index)
        );
        groupRuleContent.appendChild(removeGroupButton);

        const removeGroupIcon = createDOMElement("i", {
          class: "fas fa-times mr-1 custom-red-icon",
        });
        removeGroupButton.appendChild(removeGroupIcon);

        const removeGroupText = document.createTextNode("Remove Group");
        removeGroupButton.appendChild(removeGroupText);

        // Select box for group operator
        const groupOperatorSelect = createDOMElement("select", {
          class: "group-operator-select mt-2",
        });
        groupOperatorSelect.addEventListener("change", (e) =>
          handleOperatorForGroup(index, e.target.value)
        );
        groupRuleContent.appendChild(groupOperatorSelect);

        const selectAndOption = createDOMElement("option", { value: "AND" });
        selectAndOption.textContent = "AND";
  
        groupOperatorSelect.appendChild(selectAndOption);

        const selectOrOption = createDOMElement("option", { value: "OR" });
        selectOrOption.textContent = "OR";

        groupOperatorSelect.appendChild(selectOrOption);
        
        groupOperatorSelect.value = rule.groupOperator;
        
        const buttonRow = createDOMElement("div", {
          class: "button-row flex mt-3",
        });
        groupRuleContent.appendChild(buttonRow);

        const addAndButton = createDOMElement("button", {
          class: "button flex items-center",
        });
        addAndButton.addEventListener("click", () =>
          handleAddRuleToGroup(index, "AND")
        );
        buttonRow.appendChild(addAndButton);

        const addAndIcon = createDOMElement("i", { class: "fas fa-plus mr-1" });
        addAndButton.appendChild(addAndIcon);

        const addAndText = document.createTextNode("AND");
        addAndButton.appendChild(addAndText);

        const addOrButton = createDOMElement("button", {
          class: "button flex items-center ml-3",
        });
        addOrButton.addEventListener("click", () =>
          handleAddRuleToGroup(index, "OR")
        );
        buttonRow.appendChild(addOrButton);

        const addOrIcon = createDOMElement("i", { class: "fas fa-plus mr-1" });
        addOrButton.appendChild(addOrIcon);

        const addOrText = document.createTextNode("OR");
        addOrButton.appendChild(addOrText);

        rule.rules.forEach((nestedRule, nestedIndex) => {
          
          const filterRule = createDOMElement("div", {
            class: "filter-rule mt-3",
          });
          groupRuleContent.appendChild(filterRule);

          const ColumnLabel = createDOMElement("label");

          ColumnLabel.textContent = "Column : ";

          filterRule.appendChild(ColumnLabel);

          const filterColumn = createDOMElement("select", {
            class: "filter-column",
          });
          filterColumn.addEventListener("change", (e) =>
            handleRuleChangeForGroup(
              index,
              nestedIndex,
              "column",
              e.target.value
            )
          );
          filterRule.appendChild(filterColumn);

          const selectColumnOption = createDOMElement("option", { value: "" });
          selectColumnOption.textContent = "Select Column";
          filterColumn.appendChild(selectColumnOption);

          const abcColumnOption = createDOMElement("option", { value: "id" });
          abcColumnOption.textContent = "id";
          filterColumn.appendChild(abcColumnOption);

          const defColumnOption = createDOMElement("option", { value: "name" });
          defColumnOption.textContent = "name";
          filterColumn.appendChild(defColumnOption);

          filterColumn.value = nestedRule.column;

          const OperatorLabel = createDOMElement("label");

          OperatorLabel.textContent = "Operator : ";

          OperatorLabel.style.marginLeft = "10px";

          filterRule.appendChild(OperatorLabel);

          const filterOperator = createDOMElement("select", {
            class: "filter-operator",
          });
          filterOperator.addEventListener("change", (e) =>
            handleRuleChangeForGroup(
              index,
              nestedIndex,
              "operator",
              e.target.value
            )
          );
          filterRule.appendChild(filterOperator);

          const selectOperatorOption = createDOMElement("option", {
            value: "",
          });
          selectOperatorOption.textContent = "Select Operator";
          filterOperator.appendChild(selectOperatorOption);

          const abcOperatorOption = createDOMElement("option", {
            value: "=",
          });
          abcOperatorOption.textContent = "=";
          filterOperator.appendChild(abcOperatorOption);

          const defOperatorOption = createDOMElement("option", {
            value: ">",
          });
          defOperatorOption.textContent = ">";
          filterOperator.appendChild(defOperatorOption);

          filterOperator.value = nestedRule.operator;

          const ValueLabel = createDOMElement("label");

          ValueLabel.textContent = "Value : ";

          ValueLabel.style.marginLeft = "10px";

          filterRule.appendChild(ValueLabel);

          const filterValue = createDOMElement("input", {
            type: "number",
            class: "filter-value",
          });

          filterValue.placeholder = "Enter Value";

          filterValue.addEventListener("change", (e) =>
            handleRuleChangeForGroup(
              index,
              nestedIndex,
              "value",
              e.target.value
            )
          );
          filterValue.value = nestedRule.value;
          filterRule.appendChild(filterValue);

          const deleteRuleButton = createDOMElement("button", {
            class: "delete-rule ml-3",
          });
          deleteRuleButton.addEventListener("click", () =>
            handleDeleteRuleFromGroup(index, nestedIndex)
          );
          filterRule.appendChild(deleteRuleButton);

          const deleteRuleIcon = createDOMElement("i", {
            class: "fas fa-times custom-red-icon",
          });
          deleteRuleButton.appendChild(deleteRuleIcon);
        });

      } else {
      //   const filterRule = createDOMElement("div", {
      //     class: "single-rule p-2 mt-3 flex items-center",
      //   });
      //   filterRulesContainer.appendChild(filterRule);

      //    // Add the arrow element
      //    const arrow = createDOMElement("div", {
      //     class: "group-arrow",
      //   });
      //   filterRule.appendChild(arrow);

      //   const filterColumn = createDOMElement("select", {
      //     class: "filter-column",
      //   });
      //   filterColumn.addEventListener("change", (e) =>
      //     handleRuleChange(index, "column", e.target.value)
      //   );
      //   filterRule.appendChild(filterColumn);

      //   const selectColumnOption = createDOMElement("option", { value: "" });
      //   selectColumnOption.textContent = "Select Column";
      //   filterColumn.appendChild(selectColumnOption);

      //   const abcColumnOption = createDOMElement("option", { value: "id" });
      //   abcColumnOption.textContent = "id";
      //   filterColumn.appendChild(abcColumnOption);

      //   const defColumnOption = createDOMElement("option", { value: "name" });
      //   defColumnOption.textContent = "name";
      //   filterColumn.appendChild(defColumnOption);

      //   filterColumn.value = rule.column;

      //   const filterOperator = createDOMElement("select", {
      //     class: "filter-operator ml-3",
      //   });
      //   filterOperator.addEventListener("change", (e) =>
      //     handleRuleChange(index, "operator", e.target.value)
      //   );
      //   filterRule.appendChild(filterOperator);

      //   const selectOperatorOption = createDOMElement("option", { value: "" });
      //   selectOperatorOption.textContent = "Select Operator";
      //   filterOperator.appendChild(selectOperatorOption);

      //   const abcOperatorOption = createDOMElement("option", { value: "=" });
      //   abcOperatorOption.textContent = "=";
      //   filterOperator.appendChild(abcOperatorOption);

      //   const defOperatorOption = createDOMElement("option", { value: ">" });
      //   defOperatorOption.textContent = ">";
      //   filterOperator.appendChild(defOperatorOption);

      //   filterOperator.value = rule.operator;

      //   const filterValue = createDOMElement("input", {
      //     type: "number",
      //     class: "filter-value ml-3",
      //   });
      //   filterValue.addEventListener("change", (e) =>
      //     handleRuleChange(index, "value", e.target.value)
      //   );
      //   filterValue.value = rule.value;
      //   filterRule.appendChild(filterValue);

      //   const deleteRuleButton = createDOMElement("button", {
      //     class: "delete-rule ml-3",
      //   });
      //   deleteRuleButton.addEventListener("click", () =>
      //     handleDeleteRule(index)
      //   );
      //   filterRule.appendChild(deleteRuleButton);

      //   const deleteRuleIcon = createDOMElement("i", { class: "fas fa-times custom-red-icon" });
      //   deleteRuleButton.appendChild(deleteRuleIcon);
      // }

      const filterRule = createDOMElement("div", {
        class: "single-rule p-2 mt-3 flex items-center",
      });
      filterRulesContainer.appendChild(filterRule);

      // Add the arrow element
      const arrow = createDOMElement("div", {
        class: "group-arrow",
      });
      filterRule.appendChild(arrow);

      // Label for Column
      const columnLabel = createDOMElement("label", {
        class: "filter-label",
      });
      columnLabel.textContent = "Column : ";
      filterRule.appendChild(columnLabel);

      const filterColumn = createDOMElement("select", {
        class: "filter-column",
      });
      filterColumn.addEventListener("change", (e) =>
        handleRuleChange(index, "column", e.target.value)
      );
      filterRule.appendChild(filterColumn);

      // Adding options to filterColumn
      const selectColumnOption = createDOMElement("option", { value: "" });
      selectColumnOption.textContent = "Select Column";
      filterColumn.appendChild(selectColumnOption);

      const abcColumnOption = createDOMElement("option", { value: "id" });
      abcColumnOption.textContent = "id";
      filterColumn.appendChild(abcColumnOption);

      const defColumnOption = createDOMElement("option", { value: "name" });
      defColumnOption.textContent = "name";
      filterColumn.appendChild(defColumnOption);

      filterColumn.value = rule.column;

      // Label for Operator : 
      const operatorLabel = createDOMElement("label", {
        class: "filter-label ml-3",
      });
      operatorLabel.textContent = "Operator : ";
      filterRule.appendChild(operatorLabel);

      const filterOperator = createDOMElement("select", {
        class: "filter-operator",
      });
      filterOperator.addEventListener("change", (e) =>
        handleRuleChange(index, "operator", e.target.value)
      );
      filterRule.appendChild(filterOperator);

      // Adding options to filterOperator
      const selectOperatorOption = createDOMElement("option", { value: "" });
      selectOperatorOption.textContent = "Select Operator";
      filterOperator.appendChild(selectOperatorOption);

      const abcOperatorOption = createDOMElement("option", { value: "=" });
      abcOperatorOption.textContent = "=";
      filterOperator.appendChild(abcOperatorOption);

      const defOperatorOption = createDOMElement("option", { value: ">" });
      defOperatorOption.textContent = ">";
      filterOperator.appendChild(defOperatorOption);

      filterOperator.value = rule.operator;

      // Label for Value
      const valueLabel = createDOMElement("label", {
        class: "filter-label ml-3",
      });
      valueLabel.textContent = "Value : ";
      filterRule.appendChild(valueLabel);

      const filterValue = createDOMElement("input", {
        type: "number",
        class: "filter-value",
      });
      filterValue.placeholder = " Enter Value";
      filterValue.addEventListener("change", (e) =>
        handleRuleChange(index, "value", e.target.value)
      );
      filterValue.value = rule.value;
      filterRule.appendChild(filterValue);

      // Adding delete rule button and icon
      const deleteRuleButton = createDOMElement("button", {
        class: "delete-rule ml-3",
      });
      deleteRuleButton.addEventListener("click", () =>
        handleDeleteRule(index)
      );
      filterRule.appendChild(deleteRuleButton);

      const deleteRuleIcon = createDOMElement("i", {
        class: "fas fa-times custom-red-icon",
      });
      deleteRuleButton.appendChild(deleteRuleIcon);
    }
    });
  };

  function handleLabelClick(label) {
  filterRules.length = 0;
  const checkboxId = label.getAttribute('for');
  var alertId = checkboxId.replace("checkbox_", "");
  console.log(alertId);
  rightSide.classList.remove("hidden");
  rightSide.classList.add("visible");
  let rightRand = document.getElementById("right-random");
  rightRand.classList.add("hidden");

  alertIDForLabel = alertId;

  document.getElementById("submitButton").innerHTML = "Save";
  document.getElementById("submitButton").value = "Save";

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
    document.getElementById("whereClause").innerHTML = data.data[0].Q_FILTER;
    document.getElementById("id").value = data.data[0].ALERT_ID;
    document.getElementById("name").value = data.data[0].ALERT;
    document.getElementById("amountInput").value = data.data[0].AMOUNT;
    document.getElementById("eventDurationInput").value = data.data[0].CALLDURATION;
    document.getElementById("dataVolumeInput").value = data.data[0].DATAVOLUME;
    document.getElementById("eventCountInput").value = data.data[0].EVENTCOUNT;

  if(data.data[0].Q_FILTER !== null){

  console.log("Has Where Clause")

  const startIndex = data.data[0].Q_FILTER.indexOf("WHERE") + 6;
  
  const queryStringWithoutWhere = data.data[0].Q_FILTER.substr(startIndex);

  const conditions = queryStringWithoutWhere.split(/\s+(OR|AND)\s+(?![^(]*\))/);

  const pattern = /\(([^)]+)\)/;


  conditions.forEach((condition,index) => {
    if(condition === "AND" || condition === "OR"){
      null;
    }else{
    if (pattern.test(condition)) {
    console.log("This is Group");

    const pattern = /^\(\s*|\s*\)$/g;

    const result = condition.replace(pattern, '');

    const UpdatedArray = result.split(/\s+(AND|OR)\s+/);

    const newGroupRule = {
      type: "GROUP",
      groupOperator:conditions[index + 1],
      rules: [],
    };

    console.log("updatedArray",UpdatedArray)

      UpdatedArray.forEach((element,ind) => {
        if(element === "AND" || element === "OR"){
          null;
        }else{
          console.log("Element",element);
        const parts = element.split(" ");
        const newRule = {
        type: UpdatedArray[ind + 1],
        column: parts[0],
        operator: parts[1],
        value: parts[2],
        };
        newGroupRule.rules.push(newRule);
        }
      });
      
      filterRules.push(newGroupRule);
      render();
      }else{

        if(index === (conditions.length - 1)){
        const parts = condition.split(" ");
        const newRule = {
          type: parts[3],
          column: parts[0],
          operator: parts[1],
          value: parts[2],
        };
        filterRules.push(newRule);
        render();
        }else{

        const parts = condition.split(" ");
        const newRule = {
          type: conditions[index + 1],
          column: parts[0],
          operator: parts[1],
          value: parts[2],
        };
        filterRules.push(newRule);
        render();
        }
      }
    }
  });
  }else{
    console.log("This has no WHere Clause")
    filterRules.length = 0;
    filterRulesContainer.innerHTML = "";
  }


    const selectIntervalElement = document.getElementById("interval");

    if(data.data[0].INTERVAL !== null){

  const fetchedValueOfInterval = data.data[0].INTERVAL;

  for (let i = 0; i < selectIntervalElement.options.length; i++) {
  const option = selectIntervalElement.options[i];
  
    if (option.value === fetchedValueOfInterval) {
    option.selected = true;
    break;
      }
    }
  }else{

    for (let i = 0; i < selectIntervalElement.options.length; i++) {
    const option = selectIntervalElement.options[i];
  
    if (option.value === "Select") {
    option.selected = true;
    break;
      }
    }
  }

  const selectSeverityElement = document.getElementById("severity");

  if(data.data[0].INTERVAL !== null){
  const fetchedValueOfSeverity = data.data[0].SEVERITY;

  for (let i = 0; i < selectSeverityElement.options.length; i++) {
  const option = selectSeverityElement.options[i];
  
    if (option.value === fetchedValueOfSeverity) {
    option.selected = true;
    break;
      }
    }
  }else{
    
    for (let i = 0; i < selectSeverityElement.options.length; i++) {
    const option = selectSeverityElement.options[i];
  
    if (option.value === "Select") {
    option.selected = true;
    break;
      }
    }
  }

  const selectStatusElement = document.getElementById("status");

  if(data.data[0].INTERVAL !== null){

  const fetchedValueOfStatus = data.data[0].STATUS;

  for (let i = 0; i < selectStatusElement.options.length; i++) {
  const option = selectStatusElement.options[i];
  
    if (option.value === fetchedValueOfStatus) {
    option.selected = true;
    break;
      }
    }
  }else{
    for (let i = 0; i < selectStatusElement.options.length; i++) {
    const option = selectStatusElement.options[i];
  
    if (option.value === "Select") {
    option.selected = true;
    break;
      }
    }
  }
  })
  .catch(error => {
    hideLoader();
    console.error("Error",error.stack);
  });
}
  
  function showLoader() {
    document.getElementById("custom-loader").style.display = "block";
  }

  function hideLoader() {
    document.getElementById("custom-loader").style.display = "none";
  }
  const createDOMElement = (tag, attributes) => {
    const element = document.createElement(tag);
    for (const key in attributes) {
      element.setAttribute(key, attributes[key]);
    }
    return element;
  };

    function handleCheckboxClick(checkbox) {
        var alertId = checkbox.getAttribute("data-alertid");

        console.log("Checkbox Alert ID: " + alertId);

        alertIDForCheckbox = alertId;
    }

    document.addEventListener("DOMContentLoaded", function () {
    let i=0;
    const addButton = document.getElementById("addButton");
    const deleteButton = document.getElementById("deleteButton");
    const rows = document.querySelectorAll(".row");
    const alarmName = document.getElementById("alarmName");

    addButton.addEventListener("click", () => {
    let rightRand = document.getElementById("right-random");
    rightRand.classList.add("hidden");
    showLoader();
    fetch('getMaxId.php', {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json',
    },
  })
  .then(response => response.json())
  .then(data => {
    console.log("Response",data);
    console.log("Max Alert Id",data.alert_id);
    hideLoader();
    rightSide.classList.add("visible");
    rightSide.classList.remove("hidden");
    document.getElementById("submitButton").innerHTML = "Add";
    document.getElementById("submitButton").value = "Add";
    document.getElementById("id").value = data.alert_id;
    document.getElementById("filterRulesContainer").innerHTML = "";
    document.getElementById("whereClause").innerHTML = "";
    document.getElementById("name").value = "";
    document.getElementById("interval").value = "Select";
    document.getElementById("severity").value = "Select";
    document.getElementById("status").value = "Select";
    document.getElementById("amountInput").value = "";
    document.getElementById("eventDurationInput").value = "";
    document.getElementById("dataVolumeInput").value = "";
    document.getElementById("eventCountInput").value = "";
  })
  .catch(error => {
    console.error("Error",error.stack);
    hideLoader();
  });
  });
  
  deleteButton.addEventListener("click", () => {
    showLoader();
    fetch('DeleteAlarm.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: 'alert_id=' + encodeURIComponent(alertIDForCheckbox),
  })
  .then(response => response.json())
  .then(data => {
    location.reload();
    console.log("Response",data);
    rightSide.classList.add("hidden");
    rightSide.classList.remove("visible");
    hideLoader();
  })
  .catch(error => {
    console.error("Error",error.stack);
  });
  });

  const addGroupButton = document.getElementById("addGroupButton");
  const addAndButton = document.getElementById("addAndButton");
  const addOrButton = document.getElementById("addOrButton");

  addGroupButton.addEventListener("click", () => handleAddGroup());
  addAndButton.addEventListener("click", () => handleAddRule("AND"));
  addOrButton.addEventListener("click", () => handleAddRule("OR"));

  handleRowClick(1);

  const whereClauseContainer = document.getElementById("whereClauseContainer");
  const whereClause = document.getElementById("whereClause");
  const showWhereButton = document.getElementById("showWhereButton");

  const generateWhereClause = () => {
  const conditions = [];

  console.log("FilterRules",filterRules);

  filterRules.forEach((frules, index) => {
    if(frules.type === 'GROUP'){
      console.log("FilterRules",filterRules)
        const arrayAsString = frules.rules
      .map((obj,ind) => {
          if(ind === 0){
          return `${obj.column} ${obj.operator} ${obj.value}`;
          }else{
          return `${obj.type} ${obj.column} ${obj.operator} ${obj.value}`;
          }
      })
      .join(' ');
      if(index === 0){
        const finalString = `(${arrayAsString})`
        conditions.push(finalString);
      }else{
        const finalString = `${frules.groupOperator} (${arrayAsString})`
        conditions.push(finalString);
      }
    } else {
      if(index === 0){
      const simpleString = `${frules.column} ${frules.operator} ${frules.value}`
      conditions.push(simpleString);
      }else{
        const simpleString = `${frules.type} ${frules.column} ${frules.operator} ${frules.value}`
        conditions.push(simpleString);
      }
    }
  });

  console.log(conditions);

  return conditions.length ? `WHERE ${conditions.join(" ")}` : "";
};


  const updateWhereClause = () => {
    const sqlWhere = generateWhereClause();
    document.getElementById("q_filter").value = sqlWhere;
    whereClause.textContent = sqlWhere;
    whereClause.value = sqlWhere;
  };

  const toggleWhereClause = () => {
    // whereClauseContainer.classList.toggle("hidden");
    updateWhereClause();
  };

  showWhereButton.addEventListener("click", toggleWhereClause);

  render();

});

  </script>
</body>
