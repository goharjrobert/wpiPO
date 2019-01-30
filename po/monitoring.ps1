### SET FOLDER TO WATCH + FILES TO WATCH + SUBFOLDERS YES/NO
$watcher = New-Object System.IO.FileSystemWatcher
$watcher.Path = "C:\Bitnami\wampstack-7.1.21-0\apache2\htdocs\po\"
$watcher.Filter = "Log.csv"
$watcher.IncludeSubdirectories = $true
$watcher.EnableRaisingEvents = $true

### DEFINE ACTIONS AFTER AN EVENT IS DETECTED
$action = { Copy-Item "C:\Bitnami\wampstack-7.1.21-0\apache2\htdocs\po\Log.csv" -Destination "\\pdxvault1\wpivault\IT\Documents\PO's\" }

### DECIDE WHICH EVENTS SHOULD BE WATCHED
Register-ObjectEvent $watcher "Created" -Action $action
Register-ObjectEvent $watcher "Changed" -Action $action
Register-ObjectEvent $watcher "Deleted" -Action $action
Register-ObjectEvent $watcher "Renamed" -Action $action
while ($true) {sleep 5}