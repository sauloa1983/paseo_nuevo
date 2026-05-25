<div class="listings-container list-layout">
    @forelse($properties as $property)
        <div class="listing-item">
            <a href="#" class="listing-img-container">
                <div class="listing-badges">
                    <span class="featured">Featured</span>
                    <span>{{$property->codigo}}</span>
                </div>

                <div class="listing-img-content">
                    <span class="listing-price">$275,000 <i>$520 / sq ft</i></span>
                    <span class="like-icon with-tip" data-tip-content="Add to Bookmarks"></span>
                    <span class="compare-button with-tip" data-tip-content="Add to Compare"></span>
                </div>

                <div class="listing-carousel">
                    <div><img src="images/listing-01.jpg" alt=""></div>
                    <div><img src="images/listing-01b.jpg" alt=""></div>
                    <div><img src="images/listing-01c.jpg" alt=""></div>
                </div>
            </a>
            <div class="listing-content">

                <div class="listing-title">
                    <h4><a href="single-property-page-1.html">{{ $property->tipo_inmueble->tipo ?? 'Tipo no asignado' }} - {{ $property->barrio->nombre ?? 'Barrio no asignado'  }}</a></h4>
                    
                        <i class="fa fa-map-marker"></i>
                        {{ optional($property->ciudad()->first())->nombre ?? 'No asignada' }}

                    <a href="single-property-page-1.html" class="details button border">Detalles</a>
                </div>

                <ul class="listing-details">
                    <li>{{ $property->area_construida }} m²</li>
                    <li>1 Bedroom</li>
                    <li>3 Rooms</li>
                    <li>{{ $property->no_banos }} Baños</li>
                </ul>

                <div class="listing-footer">
                    <a href="#"><i class="fa fa-user"></i> David Strozier</a>
                    <span><i class="fa fa-calendar-o"></i> 1 day ago</span>
                </div>

            </div>
        </div>
        <div class="clearfix"></div>
    @empty
        <div class="col-12">
            <div class="alert alert-info text-center" style="padding: 100px 0;">
                <span class="im im-icon-Search-onCloud ico-big"></span>
                <h1>No encontramos lo que buscas</h1>
                <p>Lo sentimos, no hay resultados que coincidan con tu búsqueda. Intenta ajustar los filtros.</p>
            </div>
        </div>
    @endforelse

    <!-- Paginación AJAX automática -->
    <div class="pagination-container margin-top-20">
        {{ $properties->links() }}
    </div>
</div>


