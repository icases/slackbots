# PBD Slack slash commands

This set of commands allow you to rapidly retrieve structures from Uniprot Ids, an visit their pdb pages

##usage

you can either add a Uniprot ID or a PDB ID


###/pdb Uniprot ID

will return a linked list of structures for this protein

###/pdb PBD ID

will return some basic info:

- a small description
- proteins and proteins segments included and the corresponding chains
- a static image of the structure
- a link to PDB

### Multiple IDs
if you provide more than one id, a new response will be generated for each one.


##HELP

### /pdb help 

this will you this message