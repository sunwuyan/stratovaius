echo ^<?xml version="1.0" encoding="ISO-8859-1"?^> >svn.xml
echo ^<?xml-stylesheet type="text/xsl" href="svn.xsl"?^> >>svn.xml
echo ^<DirectMail^> >>svn.xml
svn log https://srbac.googlecode.com/svn/trunk/ --username spyros@valor.gr --password tz6xp6ep4Pz7 --xml --incremental >>svn.xml
echo ^</DirectMail^> >>svn.xml
pause