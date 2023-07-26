<div>

    <script>

        window.addEventListener('livewire:load', function () {


            // Get the liveEditIframeWindow
            var liveEditIframeWindow = mw.top().app.canvas.getWindow();

            // Function to get elements with class .module and their attributes
            function getModulesAndAttributesWithChildren() {
                // Select all elements with class .module in liveEditIframeWindow
                var modules = liveEditIframeWindow.document.querySelectorAll('.module-layouts');

                // Array to store the data of all modules
                var modulesData = [];

                // Loop through each module and extract all attributes
                modules.forEach(function (module) {
                    var moduleAttributes = module.attributes;

                    // Convert the attributes collection to an object
                    var moduleData = {};
                    for (var i = 0; i < moduleAttributes.length; i++) {
                        var attributeName = moduleAttributes[i].name;
                        var attributeValue = moduleAttributes[i].value;
                        moduleData[attributeName] = attributeValue;
                    }

                    // Find all children with class .module within the current module
                    var childModules = module.querySelectorAll('.module');

                    // Array to store the data of all child modules
                    var childModulesData = [];

                    // Loop through each child module and extract all attributes
                    childModules.forEach(function (childModule) {
                        var childModuleAttributes = childModule.attributes;

                        // Convert the attributes collection to an object for the child module
                        var childModuleData = {};
                        for (var j = 0; j < childModuleAttributes.length; j++) {
                            var childAttributeKey = childModuleAttributes[j].name;
                            var childAttributeValue = childModuleAttributes[j].value;
                            childModuleData[childAttributeKey] = childAttributeValue;
                        }

                        // Add the child module data to the childModulesData array
                        childModulesData.push(childModuleData);
                    });

                    // Add the childModulesData to the main moduleData object
                    moduleData.childModules = childModulesData;

                    // Add the moduleData to the modulesData array
                    modulesData.push(moduleData);
                });

                return modulesData;
            }


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
            //  var modulesData = getModulesAndAttributes();
            var modulesData = getModulesAndAttributesWithChildren();


            Livewire.emit('onLoaded', {
                modules: modulesData,
            });


        });

    </script>
    <div>

        <div wire:loading>
            Loading...
        </div>
        <div id="wrapper-{{ $modulesListKey  }}">

            <livewire:microweber-live-edit::sidebar-admin-modules-list :modulesData="$modulesData"
                                                                       :wire:key="$modulesListKey"/>


        </div>


    </div>

</div>
