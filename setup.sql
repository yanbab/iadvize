
--
-- Structure de la table `vdm`
--

CREATE TABLE IF NOT EXISTS `vdm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_vdm` int(11) NOT NULL,
  `content` text NOT NULL,
  `date` datetime NOT NULL,
  `author` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
