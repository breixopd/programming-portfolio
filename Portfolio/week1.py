# hello
# print("Hello world")

# hello 2
# print("Hello Breixo")


# convert centigrade to fahrenheit
def convert(method=None, temp=None):
    try:
        if method.lower() == "f":
            return (temp - 32) * 5.0 / 9.0
        elif method.lower() == "c":
            return (temp * 9.0 / 5.0) + 32
    except:
        return "Convert celsius to fahrenheit and vice-versa,\nmethod needs to be 'c' or 'f', example: convert('c', " \
               "100)\n'c' - Celsius\n'f' - Fahrenheit\n"


# print(convert("f", 38.4))


# use baseball api to fetch player stats from name (read portfolio question wrong)
def baseball_player(name=None, year="2019"):
    try:
        import requests
        import json
        # set headers
        headers = {
            'content-type': "application/json"
        }
        url = f"http://lookup-service-prod.mlb.com/json/named.search_player_all.bam?sport_code='mlb'&name_part='{name}'"
        response = requests.get(url)
        print(json.loads(response.text))
        data = json.loads(response.text)
        # get player id
        player_id = data["search_player_all"]["queryResults"]["row"]["player_id"]
        # get player stats
        url = f"http://lookup-service-prod.mlb.com/json/named.sport_hitting_tm.bam?league_list_id='mlb'&game_type='R'&season='{year}'&player_id='{player_id}'"
        response = requests.get(url)
        data = json.loads(response.text)
        # get player stats
        stats = data["sport_hitting_tm"]["queryResults"]["row"]
        # print stats
        print(f"""
Player: {name}
Games: {stats['g']}
At Bats: {stats['ab']}
Runs: {stats['r']}
Hits: {stats['h']}
Doubles: {stats['d']}
Triples: {stats['t']}
Home Runs: {stats['hr']}
Strikeouts: {stats['so']}
""")
    except:
        return "Get baseball player stats from name and league year, example: baseball_player('Geoffrey Boycott', " \
               "'1966')\n "


# baseball_player("Geoffrey Boycott", "1966")


# work out Geoffrey Boycott batting average
def batting_average():
    matches = 609
    batted = 1014
    not_out = 162
    runs = 48426
    batting_average = (runs / (batted - not_out))
    print(f"""Geoffrey Boycott played {matches} matches, batted {batted} times, was not out {not_out} times, scored {runs} 
    runs, his batting average is {batting_average}""")

# batting_average()


# student group
def group(students=None):
    try:
        return "There will be " + str(students // 24) + " groups with " + str(students % 24) + " students left over"
    except:
        return "Figure out how many students are in each group if each group\nhas 24 students, example: group(20)\n"

# print(group())
