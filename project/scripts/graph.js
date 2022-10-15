function graphLoader() {
    fetch('graph.php', {
        method: 'GET',
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            let nodesArray = [];
            let edgesArray = [];

            for (let subjectName of result.subjectNames) {
                nodesArray.push({ id: subjectName, label: subjectName,  })
            }
            for (let prerequisite of result.prerequisites) {
                edgesArray.push({ from: prerequisite.prerequisite, to: prerequisite.subject, arrows: 'to' })
            }

            var options = {
                layout: {
                    hierarchical: {
                        direction: "LR",
                        sortMethod: "directed",
                        levelSeparation: 300
                    }
                },
                physics: {
                  hierarchicalRepulsion: {
                    nodeDistance: 150
                  }
                },
            }
            
            let network = new vis.Network(document.getElementById("graph"), { nodes: nodesArray, edges: edgesArray }, options);
        } else if (result.error) {
            console.error(result.error);
        }
    });
}

window.addEventListener('DOMContentLoaded', graphLoader);

fetch('./checkLogin.php')
    .then(response => response.json())
    .then(isLoggedResponse => {
        if (!isLoggedResponse.logged) {
            document.location = './index.html';
        }
    });

