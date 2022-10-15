function populateTimetable() {
    function generateHead() {
        let thead = table.createTHead();
        let row = thead.insertRow();

        let th = document.createElement('th');
        th.appendChild(document.createTextNode('Ден/Час'));
        row.appendChild(th);

        for (let hour of hours) {
            let th = document.createElement('th');
            th.appendChild(document.createTextNode(hour));
            row.appendChild(th);
        }
    }
    function generateTable() {
        for (let day of days) {
            let row = table.insertRow();

            let th = document.createElement('th');
            th.appendChild(document.createTextNode(day));
            row.appendChild(th);

            for (hour in hours) {
                let cell = row.insertCell();
                cell.draggable = true;
                //cell.contentEditable = true;

                let text = document.createTextNode('');
                cell.appendChild(text);
            }
        }
    }

    const table = document.getElementById('timetable');

    const days = ['Понеделник', 'Tuesday', 'Сряда', 'Четвъртък', 'Петък'];
    const hours = ['8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18']

    generateHead();
    generateTable();
}
function populateSubjects(subjects) {
    const table = document.getElementById('subjects');

    for (let subjectPair of subjects) {
        let row = table.insertRow();
        for (let i = 0; i < subjectPair.hours; i++) {
            let cell = row.insertCell();
            cell.draggable = true;
    
            let text = document.createTextNode(subjectPair.subject);
            cell.appendChild(text);
        }
    }
}
function addListeners() {
    var dragSrcEl = null;
    
    function handleDragStart(e) {
        this.style.opacity = '0.4';

        dragSrcEl = this;

        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/html', this.innerHTML);
    }
    function handleDragOver(e) {
        if (e.preventDefault) {
            e.preventDefault();
        }

        e.dataTransfer.dropEffect = 'move';

        return false;
    }

    function handleDragEnter(e) {
        this.classList.add('over');
    }
    function handleDragLeave(e) {
        this.classList.remove('over');
    }
    function handleDragEnd(e) {
        this.style.opacity = '1';

        items.forEach(function (item) {
            item.classList.remove('over');
        });
    }

    function handleDrop(e) {
        if (e.stopPropagation) {
            e.stopPropagation(); // stops the browser from redirecting
        }

        if (dragSrcEl != this) {
            dragSrcEl.innerHTML = this.innerHTML;
            this.innerHTML = e.dataTransfer.getData('text/html');
        }

        return false;
    }

    let items = document.querySelectorAll('td');
    items.forEach(function (item) {
        item.addEventListener('dragstart', handleDragStart);
        item.addEventListener('dragenter', handleDragEnter);
        item.addEventListener('dragover', handleDragOver);
        item.addEventListener('dragleave', handleDragLeave);
        item.addEventListener('drop', handleDrop);
        item.addEventListener('dragend', handleDragEnd);
    });
}

function getSubjects(subjects) {
    return subjects.map(x => ({subject: x, hours: 6}));
}
function populate(subjects) {
    let prevTimetable = document.getElementById('timetable');
    if (prevTimetable) {
        prevTimetable.remove();
    }
    let prevSubjectsTable = document.getElementById('subjects');
    if (prevSubjectsTable) {
        prevSubjectsTable.remove();
    }

    let timetable = document.createElement('table');
    timetable.id = 'timetable';
    document.body.appendChild(timetable);

    let subjectsTable = document.createElement('table');
    subjectsTable.id = 'subjects';
    document.body.appendChild(subjectsTable);

    populateTimetable();
    populateSubjects(getSubjects(subjects));
    addListeners();    
}

function planLoader(event) {
    fetch('timetable.php', {
        method: 'GET',
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            const programmeElement = document.getElementById('programme');
            for(let programme of result.programmes) {
                let option = document.createElement('option');
                option.text = programme;
                programmeElement.add(option);
            }

            const semesterElement = document.getElementById('semester');
            for(let semester of result.semesters) {
                let option = document.createElement('option');
                option.text = semester;
                semesterElement.add(option);
            }
        } else if (result.error) {
            console.error(result.error);
        }
    });
}
function submitFormHandler(event) {
    event.preventDefault();

    const formElement = event.target;
    const formData = {
        programme: formElement.querySelector('select[name="programme"]').value,
        semester: formElement.querySelector('select[name="semester"]').value,
    };

    fetch(formElement.action, {
        method: 'POST',
        body: JSON.stringify(formData),
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            populate(result.subjects);
        } else if (result.error) {
            console.error(result.error);
        }
    });
}

function toCSV() {
    let csv = [];
    let rows = document.querySelectorAll('#timetable tr');
    
    for (let i = 0; i < rows.length; i++) {
        let row = [];
        let cols = rows[i].querySelectorAll('td, th');
        
        for (let j = 0; j < cols.length; j++) {
            row.push(cols[j].innerText);
        }
        
        csv.push(row.join(','));
    }

    return csv.join('\n');
}
function downloadCSV() {
    let csv = toCSV();
    let contentType = 'text/csv';
    let csvFile = new Blob([csv], {type: contentType});
    
    let downloadLink  = document.createElement('a');
    downloadLink.download = 'timetable.csv';
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = 'none';

    document.body.appendChild(downloadLink);
    downloadLink.click();
}

function fromCSV(event) {
    function readCSV(content) {
        let allTextLines = content.split(/\r\n|\n/);
        let headers = allTextLines[0].split(',');
        let lines = [];
    
        for (let i = 1; i < allTextLines.length; i++) {
            let data = allTextLines[i].split(',');
            if (data.length == headers.length) {
    
                let tarr = [];
                for (let j = 0; j < headers.length; j++) {
                    tarr.push(data[j]);
                }
                lines.push(tarr);
            }
        }
        
        return [headers].concat(lines);
    }
    function populateFromCSV(processed) {
        let content = '';

        content += '<tr>';
        processed[0].forEach(cell => {
            content += '<th>' + cell + '</th>' ;
        });
        content += '</tr>';
        processed.shift();

        processed.forEach(row => {
            content += '<tr>';

            content += '<th>' + row[0] + '</th>' ;
            row.shift();

            row.forEach(cell => {
                content += '<td draggable="true">' + cell + '</td>' ;
            });
            content += '</tr>';
        });

        let prevTimetable = document.getElementById('timetable');
        if (prevTimetable) {
            prevTimetable.remove();
        }
        let prevSubjectsTable = document.getElementById('subjects');
        if (prevSubjectsTable) {
            prevSubjectsTable.remove();
        }    
    
        let timetable = document.createElement('table');
        timetable.id = 'timetable';
        document.body.appendChild(timetable);
        document.getElementById('timetable').innerHTML = content;
    }
    
    let file = event.target.files[0];
    let reader = new FileReader();
    reader.readAsText(file, 'UTF-8');

    reader.onload = readerEvent => {
        let content = readerEvent.target.result;

        let processed = readCSV(content);
        populateFromCSV(processed);
        addListeners();
    }
}


window.addEventListener('DOMContentLoaded', planLoader);
const form = document.querySelector('form');
form.addEventListener('submit', submitFormHandler);

const fileInput = document.getElementById('file-input');
fileInput.onchange = fromCSV;

fetch('./checkLogin.php')
    .then(response => response.json())
    .then(isLoggedResponse => {
        if (!isLoggedResponse.logged) {
            document.location = './index.html';
        }
    });
