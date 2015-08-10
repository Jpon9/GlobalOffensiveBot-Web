@extends('template.main')

@section('title', 'Modmail Listing')
@section('css-title', 'modmail-listing')

@section('content')
	<?php
		use League\CommonMark\CommonMarkConverter;
		$converter = new CommonMarkConverter();

		// Sanitizes input for the markdown converter
		function s($str) {
			$str = str_replace('<em>', '&lt;em&gt;', $str);
			$str = preg_replace('/]\s\(/', '](', $str);
			return $str;
		}
	?>
	<div id="content">
		<p class="timeframe bg-primary"><span>Showing </span>{{ $filters['start'] }} - {{ $filters['end'] < $filters['total'] ? $filters['end'] : $filters['total'] }}<span> of </span>{{ $filters['total'] }}<span> results from </span>{{ $filters['from'] }}<span> to </span>{{ $filters['to'] }}<span> of items created by </span>{{ $filters['author'] }}<span> matching a subject filter of </span>{{ $filters['subject'] }} <span> and a body filter of </span>{{ $filters['body'] }}</p>
		<p>{!! $results->render() !!}</p>
		<div class="col-md-6">
			@foreach ($results as $modmail)
			<div>
				<h4><a href="https://reddit.com/message/messages/{{ substr($modmail['_id'], 3) }}">{{ $modmail['subject'] }}</a></h4>
				<p class="byline">Sent by <a href="https://reddit.com/user/{{ $modmail['author'] }}">{{ $modmail['author'] }}</a> on {{ strftime("%d %B %Y at %H:%M:%S", $modmail['created_at']) }}</p>
				<p><?php echo $converter->convertToHtml(s($modmail['body'])); ?></p>
				@if (count($modmail['replies']) > 0)
				<div class="replies">
					@foreach ($modmail['replies'] as $reply)
					<div class="reply">
						<h5><a href="https://reddit.com/message/messages/{{ substr($modmail['_id'], 3) }}">{{ $reply['subject'] }}</a></h5>
						<p class="byline">Sent by <a href="https://reddit.com/user/{{ $reply['author'] }}">{{ $reply['author'] }}</a> on {{ strftime("%d %B %Y at %H:%M:%S", $reply['created_at']) }}</p>
						<p><?php echo $converter->convertToHtml(s($reply['body'])); ?></p>
					</div>
					@endforeach
				</div>
				@endif
			</div>
			@endforeach
		</div>
	</div>
@endsection