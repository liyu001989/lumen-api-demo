<html>
	<header></header>
	<body>
		<span>这样我们调用自己的api，返回的是对象</span>
		<ul>
			@foreach($users as $user)
			<li>{{$user->name}}</li>
			@endforeach
		</ul>
	</body>
</html>