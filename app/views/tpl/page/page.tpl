<!DOCTYPE html>
<html>
	<head>
		[%% metaInclude %%]
		[%% cssInclude %%]
		[%% jsInclude %%]
	</head>
	<body [%% IF bodyClass %%]class='[%% bodyClass %%]'[%% ENDIF %%]>
		[%% bodyContent %%]
	</body>
</html>
