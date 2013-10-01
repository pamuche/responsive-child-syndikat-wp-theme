(function($) {	$(document).ready( function() {

//	$.ajaxSetup({
//	cache : false
//	});

	$('#map')
	.vectorMap(
			{

				map : 'de_merc_en',
				backgroundColor: 'white',
				markers: syndikats_orte,
//				markers: syndikats_coords,
//				markers: [{'latLng': [51.3288,12.371], 'name': "Leipzig"}],
//				series: {
//				markers: [{
////				attribute: 'fill',
////				scale: ['#FEE5D9', '#A50F15'],
////				values: data.metro.unemployment[val],
////				min: jvm.min(metroUnemplValues),
////				max: jvm.max(metroUnemplValues)
//				},{
//				attribute: 'r',
//				scale: [1, 20],
//				values: syndikats_projekte_count
//				}]
//				},
				markerStyle: {
					initial: {
						fill: '#ffb800'
					}
				},
				regionStyle: {
					initial: {
						fill: 'white',
						"fill-opacity": 1,
						stroke: 'grey',
						"stroke-width": 1,
						"stroke-opacity": 1
					},
					hover: {
						fill: '#ffb800',
						"fill-opacity": 0.6
					}
				},
//				onLabelShow: function(event, label, code){
//					label.text('Bears, vodka, balalaika');
//				},
			});

});

})(jQuery);
