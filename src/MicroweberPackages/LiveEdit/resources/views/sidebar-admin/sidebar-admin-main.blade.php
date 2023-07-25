<div>

    <script>

        window.addEventListener('livewire:load', function () {


            // Get the liveEditIframeWindow
            var liveEditIframeWindow = mw.top().app.canvas.getWindow();

            // Function to get elements with class .module and their attributes
            function getModulesAndAttributes() {
                // Select all elements with class .module in liveEditIframeWindow
                var modules = liveEditIframeWindow.document.querySelectorAll('.module');

                // Array to store the data of all modules
                var modulesData = [];

                // Loop through each module and extract the required attributes
                modules.forEach(function (module) {
                    var moduleAttributes = module.attributes;

                    // Convert the attributes collection to an object
                    var moduleData = {};
                    for (var i = 0; i < moduleAttributes.length; i++) {
                        var attributeName = moduleAttributes[i].name;
                        var attributeValue = moduleAttributes[i].value;
                        moduleData[attributeName] = attributeValue;
                    }

                    // Add the moduleData to the modulesData array
                    modulesData.push(moduleData);
                });

                return modulesData;
            }

            // Get the module data
            var modulesData = getModulesAndAttributes();


            Livewire.emit('onLoaded', {
                modules: modulesData,
            });


        });

    </script>
    <div>


        <div>
            @dump(rand())
            @dump($modulesData)
        </div>


    </div>

</div>
