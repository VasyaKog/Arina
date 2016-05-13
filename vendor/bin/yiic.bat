@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../yiisoft/yii/framework/yiic
php "%BIN_TARGET%" %*
