# FS19 Web Stats - EARLY ALPHA VERSION - ONLY PARTIAL WORKING. 
#### Also with patch 1.2.0.1 the Farming Simulator Web API has not been extended yet. The only way to get all required data from the dedicated server is FTP access. 
#####Update 21.12.2018 - what is working so far:
- only Felsbrunn map at the moment
- get data by FTP from dedicated servers
- get data from lokal savegame should work too
- show minimal, maximum and actual prices of commodities
- show missions, sortable
- ability to join a farm (virtual - no ingame effect) to show data of a spefic farm
- show silo of a choosen farm
- show vehicles of a choosen farm

---
When it is finished, FS19 Web Stats should display a production and stock overview for the Farming Simulator 19 dedicated servers.
At the moment, the dedicated server API does not seem to be complete. It is possible to get information about
- online player
- Map name, server version, perhaps mods
- Vehicles, bales and pallets
- high demands
- some general data like ingame options, total amount of money of all farms, date/time
- Farmland and fields

It seems that data like
- Placeables (including farmsilos)
- Selling prices 
- animals 
- ...

are not available. 

With these poor data sources it is difficult to create a useful website. Hopefully Giants will add some more available data to the server api.

I start programming with local savegames for my personal use. If Giants will not more add data to their Web API, it might be an option to access savegames from dedicated servers via FTP. 
