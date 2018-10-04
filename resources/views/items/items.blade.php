@if ($items)

    <div class="row">
        @foreach ($items as $key=>$item)
            <div class="item">
                <div>
                <div class="col-md-3 col-sm-4 col-xs-12">
                <div class="panel panel-default">
                        <div class="panel-heading text-center">
                            <img src="{{ $item->image_url }}" alt="">
                        </div>
                        <div class="panel-body">
                            
                            @if ($item->id)
                                <p class="item-title"><a href="{{ route('items.show', $item->id) }}">{{ $item->name }}</a></p>
                            @else
                            
                                <p class="item-title">{{ $item->name }}</p>
                            @endif
                            
                            
                            <div class="buttons text-center">
                                @if (Auth::check())
                                  <!--wantボタン--> 
                                  @include('items.want_button', ['item' => $item])
                                  <!--have-->  
                                  @include('items.have_button', ['item' => $item])
                                @endif
                           
                           
                            
                               
                            </div>
                        </div>
                        {{--isset()で$item->countに値がりNullじゃないかチェック --}}
                        {{-- countがある時だけランキング内容を表示 --}}
                        @if (isset($item->count))
                            <div class="panel-footer">
                                {{-- 0から始まりの配列番号の$keyに+1して順位にしている --}}
                                <p class="text-center">{{ $key+1 }}位: {{ $item->count}} Wants</p>
                            </div>
                        @endif
                        
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
  
    
    
    
@endif