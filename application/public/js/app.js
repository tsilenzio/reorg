$(document).ready(function() {

	// Storage container for state and data
	var store = {
		phrase: '',
		data: {}
	}

	//---------------------------------------------
	// Components
	//---------------------------------------------

	// Updates the pagination
	var Pagination = (function() {
		$('.pagination').on('click', 'li a', function() {
			store.currentPage = $(this).text()
			Query()
		})

		// This is a quick and dirty port of Laravel's Pagination

		var lastPage
		var currentPage

		function getActivePageWrapper(text) {
			return '<li class="active"><span>' + text + '</span></li>'
		}

		function getDisabledTextWrapper(text) {
			return '<li class="disabled"><span>' + text + '</span></li>'
		}

		function getPageLinkWrapper(url, page, text, rel) {
			rel = rel == null ? '' : ' rel="' + rel + '"'

			//return '<li><a href="' + url + '"' + rel + '>' + page + '</a></li>'
			return '<li><a data-page="' + page + '" href="#"' + rel + '>' + text + '</a></li>'
		}

		function getUrl(page) {
			return '#' + page
		}

		function getLink(page) {
			var url = getUrl(page)

			return getPageLinkWrapper(url, page, page)
		}

		function getDots() {
			return getDisabledTextWrapper('...')
		}

		function getPageRange(start, end) {
			var pages = []

			for (var page = start; page <= end; page++) {
				if (currentPage == page) {
					pages.push(getActivePageWrapper(page))
				} else {
					pages.push(getLink(page))
				}
			}

			return pages.join('')
		}

		function getStart() {
			return getPageRange(1, 2) + getDots()
		}

		function getFinish() {
			var content = getPageRange(lastPage - 1, lastPage)

			return getDots() + content
		}

		function getAdjacentRange() {
			return getPageRange(currentPage - 3, currentPage + 3)
		}

		function getPageSlider() {
			var window = 6

			if (currentPage <= window) {
				var ending = getFinish()

				return getPageRange(1, window + 2) + ending
			} else if (currentPage >= lastPage - window) {
				var start = lastPage - 8
				var content = getPageRange(start, lastPage)

				return getStart() + content
			} else {
				var content = getAdjacentRange()

				return getStart() + content + getFinish()
			}
		}

		function getPrevious(text) {
			text = text || '&laquo;'

			if (currentPage <= 1) {
				return getDisabledTextWrapper(text)
			}

			var url = getUrl(currentPage - 1)

			return getPageLinkWrapper(url, currentPage - 1, text, 'prev')
		}

		function render(info) {
			var content
			lastPage = store.data.pagination.lastPage
			currentPage = store.data.pagination.currentPage

			if (lastPage < 13) {
				content = getPageRange(1, lastPage)
			} else {
				content = getPageSlider()
			}

			// return getPrevious() + content + getNext()
			$('.pagination').html(getPrevious() + content + getNext())
		}

		function getNext(text) {
			text = text || '&raquo;'

			if (currentPage >= lastPage) {
				return getDisabledTextWrapper(text)
			}

			var url = getUrl(currentPage + 1)

			return getPageLinkWrapper(url, currentPage + 1, text, 'next')
		}

		return {
			render: render
		}
	})()

	// Updates the table listing
	var Table = (function() {
		function render() {
			var $tbody = $('.table tbody').empty()

			for(var key in store.data.payments) {
				var payment = store.data.payments[key]
				var $tr = $('<tr></tr>')

				for(var prop in payment) {
					$tr.append($('<td></td>').text(payment[prop]))
				}

				$tr.appendTo($tbody)
			}
		}

		return {
			render: render
		}
	})()

	// Requests payment data from the API
	var Query = (function() {
		// XHR request reference
		var xhr

		function update() {
			// Abort previous request
			if (xhr && xhr.readyState != 4) {
				xhr.abort();
			}

			// Request data from API
			xhr = $.ajax({
				method: "POST",
				url: '/api/search',
				data: {
					phrase: store.phrase,
					page: store.currentPage,
				}
			}).done(function(data) {
				store.data = data
			}).fail(function(err) {
				// Ignore error when request aborted
				if(err.statusText === 'abort') {
					return;
				}

				// TODO :: display error message to user
				console.error(err)
			}).always(function() {
				// Update components
				Table.render()
				Pagination.render()
			})
		}

		return update
	})()

	//
	var Searchbox = (function() {
		$('#filter').on('input', function() {
			store.phrase = $(this).val()
			Query()
		})
	})()

	var DownloadButton = (function() {
		$('#xlsx').on('click', function() {
			$('<form></form>')
				.attr('method', 'POST')
				.attr('action', '/download')
				.append(
					$('<input>')
						.attr('name', 'phrase')
						.attr('value', store.phrase)
				).append(
				$('<input>')
					.attr('name', 'page')
					.attr('value', store.currentPage)
				).submit()
		})
	})()

	// Start with the first query call
	Query()
})